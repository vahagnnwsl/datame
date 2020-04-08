<?php

namespace App\Http\Controllers\Api;

use App\App;
use App\CheckingList;
use App\Http\Controllers\Controller;
use App\Packages\Constants;
use App\Packages\Loggers\ApiLog;
use App\Packages\Providers\Result;
use App\Packages\Repository\AppRepository;
use App\Packages\Transformer\AppTransformer;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

/**
 * Управления заявками пользователя
 *
 * Class AppController
 * @package App\Http\Controllers\Api
 */
class AppController extends Controller
{

    private $repository;
    private $transformer;
    private $logger;

    public function __construct()
    {
        $this->logger = ApiLog::newInstance();
        $this->repository = new AppRepository($this->logger);
        $this->transformer = new AppTransformer();
    }

    /**
     * Создать заявку
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->logger->info("app-store", $request->toArray());

        $response = (new Error())->setIdentity($this->logger->getIdentity());
        /** @var User $user */
        $user = $request->user();

        $this->logger->info('date_service: '. is_null($user->date_service) ? 'Дата окончания услуг не установлена': $user->date_service->format('d.m.Y'));
        $this->logger->info("check_quantity: {$user->check_quantity}");

        //проверяем количество проверяемых или дату окончания услуг
        if(!$this->checkUserAppPermission($user)) {
            $response->setMessage("Дата окончания услуг истекла или количество проверяемых заявок равна нулю. Обратитесь к администратору.");
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        //fix, если добавить и потом удалить на стороне веб прислывается маска.
        $dt = $request->all();
        $dt['birthday'] = isset($dt['birthday']) ? str_replace('_', '', $dt['birthday']) : null;
        $dt['passport_code'] = isset($dt['passport_code']) ? str_replace('_', '', $dt['passport_code']) : null;
        $dt['date_of_issue'] = isset($dt['date_of_issue']) ? str_replace('_', '', $dt['date_of_issue']) : null;
        $dt['code_department'] = isset($dt['code_department']) ? str_replace('_', '', $dt['code_department']) : null;
        $dt['code_department'] = !empty($dt['code_department']) ? str_replace(" ", "-", $dt['code_department']) : null;

        $validator = Validator::make($dt, [
            'lastname' => ['required', 'regex:/^[a-zA-Zа-яА-Я- ]+$/ui'],
            'name' => ['required', 'regex:/^[a-zA-Zа-яА-Я- ]+$/ui'],
//            'patronymic' => ['required', 'regex:/^[a-zA-Zа-яА-Я-]+$/ui'],
            'birthday' => 'required|date_format:d.m.Y',
            'passport_code' => 'required|regex:/^[0-9]{4}\s{1}[0-9]{6}$/',
            'date_of_issue' => 'required|date_format:d.m.Y',
            'code_department' => ['nullable', 'regex:/^[0-9]{3}[-]{1}[0-9]{3}$/']
        ]);

        if($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        $model = $this->repository->store(array_merge($validator->validated(), ['ip' => $request->ip()]), Auth::id());

        if(is_null($model)) {
            $response->setMessage("Не удалось создать заявку. Обратитесь к администратору");
            return response()->json($response, 400);
        }

        if($user->check_quantity > 0) {
            $user->check_quantity -= 1;
            $user->save();

            $model->return_check_quantity = Constants::CHECK_QUANTITY_NEED_RETURN;
            $model->save();

            $this->logger->info("new_check_quantity: {$user->check_quantity}");
        }

        $this->logger->info('created', $model->toArray());

        return response()->json($this->transformer->transform($model));
    }

    /**
     * Проверка даты окончания услуг или количества заявок пользователя
     *
     * @param User $user
     * @return bool
     */
    public function checkUserAppPermission(User $user)
    {

        if(!$user->isAdmin()) {
            if((is_null($user->date_service) || $user->date_service->lessThan(Carbon::now())) && $user->check_quantity == 0) {
                return false;
            }
        } else {
            $this->logger->info("заявку создал админ");
        }

        return true;
    }

    /**
     * Возвращает информацию по заявке
     *
     * @param Request $request
     * @param $app_id
     * @return array
     */
    public function getApp(Request $request, $app_id)
    {
        $result = new Result();

        $app = $this->repository->getAppById($app_id);

        if(is_null($app))
            return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();

        //1 - демо заявка доступ не нужен
        if($app_id != 1) {
            if($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
                return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();
        }

        //все проверки проведены успешно
        if($app->status == Constants::CHECKING_STATUS_SUCCESS || ($app->status == Constants::CHECKING_STATUS_ERROR && $app->checking_count == 3)) {

            $result->setResult($this->transformer->setExtend(true)->transform($app));
            $result->setStatusResult(true);
        } else {
            $checking_completed_success = 0;
            $data = [
                'status' => $app->status,
                'list' => $app->checkingList()->get()->transform(function(CheckingList $item) use(&$checking_completed_success) {
                    if($item->status == Constants::CHECKING_STATUS_SUCCESS) {
                        $checking_completed_success += 1;
                    }
                    return AppTransformer::transformCheckingList($item);
                })
            ];
            ;

            $data['message'] = Constants::getDescAppStatus($app, round($checking_completed_success / $app->checkingList()->count() * 100)."%");

            $result->setResult($data);
        }

        return $result->toArray();
    }

    public function getAppDemo(Request $request) {
        return $this->getApp($request, 1);
    }

    public function getAppShort(Request $request, $app_id) {
        $result = new Result();

        $app = $this->repository->getAppById($app_id);

        if(is_null($app))
            return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();

        if($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
            return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();

        return response()->json($this->transformer->setWithUser(true)->transform($app));
    }

    /**
     * Возвращает список заявок пользователя
     *
     * @param Request $request
     * @param int $page
     * @param int $limit
     * @return App[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getApps(Request $request, $page = 1, $limit = 10)
    {

        $apps = $this->repository->getByUserId($request->user()->id, $page, $limit, $request->get('searching'));

        $response = new PaginationResult($page, $apps['limit'], $apps['total']);
        $response->setItems($apps['items']->transform(function(App $app) {
            return $this->transformer->transform($app);
        })->toArray());

        return response()->json($response->toArray());
    }

    /**
     * Возвращает список всех заявок от пользователей
     *
     * @param Request $request
     * @param int $page
     * @param int $limit
     * @return App[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getAppsAll(Request $request, $page = 1, $limit = 10)
    {

        $apps = $this->repository->getAll($page, $limit, $request->get('searching'));

        $response = new PaginationResult($page, $apps['limit'], $apps['total']);
        $response->setItems($apps['items']->transform(function(App $app) {
            return $this->transformer->setWithUser(true)->transform($app);
        })->toArray());

        return response()->json($response->toArray());
    }

}
