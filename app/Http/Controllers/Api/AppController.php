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
     * @queryParam passport_code required  ex. 4513 426808
     * @queryParam date_of_issue optional ex. format 01.01.2001
     * @queryParam birthday   required ex. format 01.01.2001
     * @queryParam lastname  required
     * @queryParam name required
     * @queryParam patronymic optional
     * @response {
     * "id": 3,
     * "identity": "ee7731b232e4ebc504c4",
     * "name": "Эдуард",
     * "lastname": "Вырыпаев",
     * "birthday": "10.02.1969",
     * "patronymic": "Геннадьевич",
     * "passport_code": "4513 426808",
     * "date_of_issue": "28.02.2014",
     * "code_department": null,
     * "created_at": "13.05.2020",
     * "checking_date_last": "13.05.2020 20:12",
     * "checking_date_next": "13.05.2020 20:12",
     * "inn": null,
     * "snils": null,
     * "ip": "127.0.0.1",
     * "user": null,
     * "status": 1,
     * "services": {
     * "completed": "0%",
     * "completed_success": "0%",
     * "list": [
     * {
     * "type": 1,
     * "status": 1,
     * "message": null,
     * "name": "Проверка паспорта"
     * },
     * {
     * "type": 2,
     * "status": 1,
     * "message": null,
     * "name": "Поиск ИНН"
     * },
     * {
     * "type": 3,
     * "status": 1,
     * "message": null,
     * "name": "Задолженность перед государственными органами"
     * },
     * {
     * "type": 4,
     * "status": 1,
     * "message": null,
     * "name": "Исполнительные производства"
     * },
     * {
     * "type": 5,
     * "status": 1,
     * "message": null,
     * "name": "Интерпол, красные карточки"
     * },
     * {
     * "type": 6,
     * "status": 1,
     * "message": null,
     * "name": "Интерпол, желтые карточки"
     * },
     * {
     * "type": 7,
     * "status": 1,
     * "message": null,
     * "name": "Нахождение в списках террористов и экстремистов"
     * },
     * {
     * "type": 8,
     * "status": 1,
     * "message": null,
     * "name": "Федеральный розыск"
     * },
     * {
     * "type": 9,
     * "status": 1,
     * "message": null,
     * "name": "Местный розыск"
     * },
     * {
     * "type": 10,
     * "status": 1,
     * "message": null,
     * "name": "Дисквалифицированные лица"
     * },
     * {
     * "type": 11,
     * "status": 1,
     * "message": null,
     * "name": "Банкротство"
     * },
     * {
     * "type": 12,
     * "status": 1,
     * "message": null,
     * "name": "Руководство и учредительство"
     * },
     * {
     * "type": 13,
     * "status": 1,
     * "message": null,
     * "name": "Проверка кода подразделения"
     * }
     * ],
     * "message": "Ожидайте. Заявка поставлена в очередь на обработку!"
     * }
     * }
     */
    public function store(Request $request)
    {
        $this->logger->info("app-store", $request->toArray());

        $response = (new Error())->setIdentity($this->logger->getIdentity());
        /** @var User $user */
        $user = $request->user();

        $this->logger->info('date_service: ' . is_null($user->date_service) ? 'Дата окончания услуг не установлена' : $user->date_service->format('d.m.Y'));
        $this->logger->info("check_quantity: {$user->check_quantity}");

        //проверяем количество проверяемых или дату окончания услуг
        if (!$this->checkUserAppPermission($user)) {
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

        $rules = [
            'lastname' => ['required', 'regex:/^[a-zA-Zа-яА-Я- ]+$/ui'],
            'name' => ['required', 'regex:/^[a-zA-Zа-яА-Я- ]+$/ui'],
            'birthday' => 'required|date_format:d.m.Y',
            'passport_code' => 'required|regex:/^[0-9]{4}\s{1}[0-9]{6}$/',
            'date_of_issue' => 'required|date_format:d.m.Y',
            'code_department' => ['nullable', 'regex:/^[0-9]{3}[-]{1}[0-9]{3}$/']
        ];

        if ($request->get('patronymic')) {
            $rules['patronymic'] = ['regex:/^[a-zA-Zа-яА-Я-]+$/ui'];
        }

        $validator = Validator::make($dt, $rules);

        if ($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        $model = $this->repository->store(array_merge($validator->validated(), ['ip' => $request->ip()]), Auth::id());

        if (is_null($model)) {
            $response->setMessage("Не удалось создать заявку. Обратитесь к администратору");
            return response()->json($response, 400);
        }

        if ($user->check_quantity > 0) {
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

        if (!$user->isAdmin()) {
            if ((is_null($user->date_service) || $user->date_service->lessThan(Carbon::now())) && $user->check_quantity == 0) {
                return false;
            }
        } else {
            $this->logger->info("заявку создал админ");
        }

        return true;
    }


    /**
     * Возвращает информацию по заявке
     * @response {
     * "status": true,
     * "result": {
     * "id": 1,
     * "identity": "413e1c44f5f35086e38b",
     * "name": "Иван",
     * "lastname": "Иванов",
     * "birthday": "23.01.1986",
     * "patronymic": "Иванович",
     * "passport_code": "4508 123456",
     * "date_of_issue": "27.07.2006",
     * "code_department": "770-123",
     * "created_at": "13.05.2020",
     * "checking_date_last": "13.05.2020 17:58",
     * "checking_date_next": "13.05.2020 17:58",
     * "checking_count": 0,
     * "city_birth": null,
     * "inn": "771122334455",
     * "snils": null,
     * "ip": "127.0.0.1",
     * "user": null,
     * "status": 4,
     * "services": {
     * "completed": "100%",
     * "completed_success": "100%",
     * "list": [
     * {
     * "type": 1,
     * "status": 4,
     * "message": null,
     * "name": "Проверка паспорта"
     * },
     * {
     * "type": 2,
     * "status": 4,
     * "message": null,
     * "name": "Поиск ИНН"
     * },
     * {
     * "type": 3,
     * "status": 4,
     * "message": null,
     * "name": "Задолженность перед государственными органами"
     * },
     * {
     * "type": 4,
     * "status": 4,
     * "message": null,
     * "name": "Исполнительные производства"
     * },
     * {
     * "type": 5,
     * "status": 4,
     * "message": null,
     * "name": "Интерпол, красные карточки"
     * },
     * {
     * "type": 6,
     * "status": 4,
     * "message": null,
     * "name": "Интерпол, желтые карточки"
     * },
     * {
     * "type": 7,
     * "status": 4,
     * "message": null,
     * "name": "Нахождение в списках террористов и экстремистов"
     * },
     * {
     * "type": 8,
     * "status": 4,
     * "message": null,
     * "name": "Федеральный розыск"
     * },
     * {
     * "type": 9,
     * "status": 4,
     * "message": null,
     * "name": "Местный розыск"
     * },
     * {
     * "type": 10,
     * "status": 4,
     * "message": null,
     * "name": "Дисквалифицированные лица"
     * },
     * {
     * "type": 11,
     * "status": 4,
     * "message": null,
     * "name": "Банкротство"
     * },
     * {
     * "type": 12,
     * "status": 4,
     * "message": null,
     * "name": "Руководство и учредительство"
     * },
     * {
     * "type": 13,
     * "status": 4,
     * "message": null,
     * "name": "Проверка кода подразделения"
     * }
     * ],
     * "message": "Заявка успешно обработана"
     * },
     * "extend": {
     * "name_en": "Ivan",
     * "lastname_en": "Ivanov",
     * "patronymic_en": "Ivanovich",
     * "current_age": 34,
     * "passport": {
     * "is_valid": "Паспорт действительный",
     * "status": "Паспорт выдан вовремя",
     * "passport_date_replace": "23.12.0000",
     * "attachment": null,
     * "passport_serie_year": 1,
     * "passport_serie_region": 1,
     * "who_issue": []
     * },
     * "tax": {
     * "amount": "67,63",
     * "items": [
     * {
     * "id": 1,
     * "article": "Транспортный налог с физических лиц (пени по соответствующему платежу)",
     * "date_protocol": "14.04.2019",
     * "number": "1234567890123890",
     * "name": "МРИ ФНС России №11 по Московской области",
     * "amount": "67,63",
     * "inn": "5043024703"
     * }
     * ]
     * },
     * "fssp": {
     * "amount": "219.564,00",
     * "proceed": [
     * {
     * "id": 1,
     * "amount": "219.564,00",
     * "number": "11111/13/33/11 от 10.01.2019",
     * "name_poluch": "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л/С 05731A53600)",
     * "nazn": "плата задолженности по ИП № 11111/13/33/11 от 10.01.201",
     * "date_protocol": "10.01.2019"
     * }
     * ],
     * "finished": [
     * {
     * "id": 2,
     * "amount": "0,00",
     * "number": "22222/14/33/11 от 10.02.2019",
     * "name_poluch": "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л/С 05731A53600)",
     * "nazn": "плата задолженности по ИП № 22222/14/33/11 от 10.02.201",
     * "date_protocol": "10.02.2019"
     * }
     * ]
     * },
     * "wanted": {
     * "interpol_red": "В розыске отсутствует",
     * "interpol_yellow": "В розыске отсутствует",
     * "fed_fsm": "В розыске отсутствует",
     * "mvd_wanted": "В розыске отсутствует",
     * "fssp_wanted": "В розыске отсутствует"
     * },
     * "other": {
     * "disq": [
     * {
     * "result": "Не является дисквалифицированным лицом",
     * "period": null,
     * "start_date": null,
     * "end_date": null,
     * "org_position": null,
     * "name_org_protocol": null
     * }
     * ],
     * "debtor": [
     * {
     * "result": "Не является банкротом",
     * "category": null,
     * "ogrnip": null,
     * "snils": null,
     * "region": null,
     * "live_address": null
     * }
     * ]
     * },
     * "business": {
     * "ul": [
     * {
     * "naim_ul_sokr": "ООО \"ТТК ПЛЮС\"",
     * "naim_ul_poln": "ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ \"TTK Плюс\"",
     * "activnost": "Ликвидировано",
     * "inn": "1234567890",
     * "kpp": "773301001",
     * "obr_data": "08.02.2019",
     * "adres": "123007, гор. Москва, ул. Академика Королева, д. 12",
     * "kod_okved": "45.1",
     * "naim_okved": "Торговля автотранспортными средствами",
     * "rukovoditel": "Генеральный директор Иванов Иван Иванович"
     * }
     * ],
     * "ip": [
     * {
     * "naim_vid_ip": "Индивидуальный предприниматель",
     * "familia": "Иванов",
     * "imia": "Иван",
     * "otchestvo": "Иванович",
     * "activnost": "Действующий",
     * "innfl": "12345678905",
     * "data_ogrnip": "13.04.2004",
     * "naim_stran": "",
     * "kod_okved": "45.2",
     * "naim_okved": "Техническое обслуживание и ремонт автотранспортных средств"
     * }
     * ]
     * },
     * "trust": {
     * "all_amount": 219631.63,
     * "value": 58,
     * "services": [
     * {
     * "name": "Паспорт",
     * "status": true
     * },
     * {
     * "name": "Руководство и учредительство",
     * "status": true
     * },
     * {
     * "name": "Нахождение в розыске",
     * "status": true
     * },
     * {
     * "name": "Задолженность перед госорганами",
     * "status": true
     * },
     * {
     * "name": "Иные источники",
     * "status": true
     * },
     * {
     * "name": "Исполнительные производства",
     * "status": false
     * }
     * ],
     * "all_amount_formatted": "219.631,63",
     * "parts": [
     * {
     * "name": "Паспорт действительный",
     * "value": 40
     * },
     * {
     * "name": "Паспорт действительный, установлен инн",
     * "value": 10
     * },
     * {
     * "name": "Задолженности перед госорганами до 500",
     * "value": 8
     * },
     * {
     * "name": "2 фирмы или ИП в сумме",
     * "value": 5
     * },
     * {
     * "name": "Не является банкротом",
     * "value": 5
     * },
     * {
     * "name": "Год соответствует серии",
     * "value": 5
     * },
     * {
     * "name": "Серия соответствет региону",
     * "value": 5
     * },
     * {
     * "name": "Есть действующие фссп от 10000 рублей",
     * "value": -10
     * },
     * {
     * "name": "Не является дисквалифицированным лицом",
     * "value": 5
     * },
     * {
     * "name": "Задолженность от 100.000 до 250.000",
     * "value": -15
     * }
     * ]
     * }
     * }
     * }
     * }
     *
     */
    public function getApp(Request $request, $app_id)
    {
        $result = new Result();

        $app = $this->repository->getAppById($app_id);

        if (is_null($app))
            return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();

        //1 - демо заявка доступ не нужен
        if ($app_id != 1) {
            if ($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
                return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();
        }

        //все проверки проведены успешно
        if ($app->status == Constants::CHECKING_STATUS_SUCCESS || ($app->status == Constants::CHECKING_STATUS_ERROR && $app->checking_count == 3)) {

            $result->setResult($this->transformer->setExtend(true)->transform($app));
            $result->setStatusResult(true);
        } else {
            $checking_completed_success = 0;
            $data = [
                'status' => $app->status,
                'list' => $app->checkingList()->get()->transform(function (CheckingList $item) use (&$checking_completed_success) {
                    if ($item->status == Constants::CHECKING_STATUS_SUCCESS) {
                        $checking_completed_success += 1;
                    }
                    return AppTransformer::transformCheckingList($item);
                })
            ];;

            $data['message'] = Constants::getDescAppStatus($app, round($checking_completed_success / $app->checkingList()->count() * 100) . "%");

            $result->setResult($data);


            $result->setResult($this->transformer->setExtend(true)->transform($app));
            $result->setStatusResult(false);
        }


        return $result->toArray();
    }

    public function getAppDemo(Request $request)
    {
        return $this->getApp($request, 1);
    }

    public function getAppShort(Request $request, $app_id)
    {
        $result = new Result();

        $app = $this->repository->getAppById($app_id);

        if (is_null($app))
            return $result->setResult(['message' => 'Заявка не существует или доступ запрещен!'])->toArray();

        if ($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
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
        $response->setItems($apps['items']->transform(function (App $app) {
            return $this->transformer->transform($app);
        })->toArray());

        return response()->json($response->toArray());
    }

    /**
     * Возвращает список всех заявок от пользователей
     * @param Request $request
     * @param int $page
     * @param int $limit
     * @return App[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getAppsAll(Request $request, $page = 1, $limit = 10)
    {

        $apps = $this->repository->getAll($page, $limit, $request->get('searching'));

        $response = new PaginationResult($page, $apps['limit'], $apps['total']);
        $response->setItems($apps['items']->transform(function (App $app) {
            return $this->transformer->setWithUser(true)->transform($app);
        })->toArray());

        return response()->json($response->toArray());
    }

}
