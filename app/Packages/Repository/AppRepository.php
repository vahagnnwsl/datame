<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-29
 * Time: 15:27
 */

namespace App\Packages\Repository;


use App\App;
use App\CheckingList;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class AppRepository
{

    private $logger;

    public function __construct(CustomLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return App
     */
    public function builder()
    {
        return new App();
    }

    /**
     * Возвращает заявку
     *
     * @param $appId
     * @return App|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getAppById($appId)
    {
        return $this->builder()->where('id', $appId)->first();
    }

    /**
     * Возвращает заявку
     *
     * @param $identity
     * @return App|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getAppByIdentity($identity)
    {
        return $this->builder()->where('identity', $identity)->first();
    }

    /**
     * @param array $data
     * @param $user_id
     * @return App|null
     */
    public function store(array $data, $user_id)
    {
        try {
            return DB::transaction(function() use ($data, $user_id) {

                $app = $this->builder();
                if(isset($data['app_id_manual']))
                    $app->id = $data['app_id_manual'];

                $app->user_id = $user_id;
                $app->lastname = mb_strtolower($data['lastname']);
                $app->status = Constants::CHECKING_APP_NEW;
                $app->name = mb_strtolower($data['name']);
                $app->patronymic = mb_strtolower($data['patronymic']);
                $app->birthday = dt_parse($data['birthday']);
                $app->passport_code = $data['passport_code'];
                $app->date_of_issue = dt_parse($data['date_of_issue']);
                $app->code_department = $data['code_department'];
                $app->checking_date_last = Carbon::now();
                $app->checking_date_next = Carbon::now();
                $app->identity = $this->logger->getIdentity();
                $app->ip = $data['ip'];

                if($app->save()) {
                    //регистрируем все сервисы для проверки
                    $services = collect(CheckingList::getServices())->transform(function($type_id) {
                        return new CheckingList([
                            'type' => $type_id,
                            'status' => Constants::CHECKING_STATUS_NEW
                        ]);
                    });
                    $app->checkingList()->saveMany($services);

                    return $app;
                }

                return null;
            });
        } catch(Throwable $e) {
            $this->logger->error($e->getMessage());
        }

        return null;
    }

    /**
     * Список всех заявок с фильтром по пользователю
     *
     * @param $user_id
     * @param int $page
     * @param int $limit
     * @param string $searching
     * @return array
     */
    public function getByUserId($user_id, $page = 1, $limit = 10, $searching = "")
    {
        $builder = $this->builder()->where('user_id', $user_id);

        if(!empty($searching)) {
            $builder = $builder->where(function(Builder $query) use ($searching) {
                return $query->where('name', 'like', "%{$searching}%")
                    ->orWhere('lastname', 'like', "%{$searching}%")
                    ->orWhere('id', 'like', "%{$searching}%");
            });
        }
        $total = $builder->count();

        $builder->orderByDesc('id');

        return [
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'items' => $builder->skip(($page - 1) * $limit)->take($limit)->get()
        ];
    }

    /**
     * Список всех заявок без фильтрации по пользователю
     *
     * @param int $page
     * @param int $limit
     * @param string $searching
     * @return array
     */
    public function getAll($page = 1, $limit = 10, $searching = "")
    {
        $searching = mb_strtolower($searching);
        $builder = DB::table('apps')->orderByDesc('id');

        if(!empty($searching)) {
//            $builder = $builder->with('user')->where(function(Builder $query) use ($searching) {
//                return $query->whereRaw('lower(name) like (?)', ["%{$searching}%"])
//                    ->orWhereRaw('lower(lastname) like (?)', ["%{$searching}%"])
//                    ->orWhereRaw('lower(id) like (?)', ["%{$searching}%"])
//                    ->orWhereRaw('lower(ip) like (?)', ["%{$searching}%"]);
//            })->orWhereHas('user', function(Builder $query) use ($searching) {
//                return $query->whereRaw('lower(name) like (?)', ["%{$searching}%"])
//                    ->orWhereRaw('lower(lastname) like (?)', ["%{$searching}%"])
//                    ->orWhereRaw('lower(email) like (?)', ["%{$searching}%"]);
//            });

            $builder = $builder
                ->join('users', 'apps.user_id', 'users.id')
                ->whereRaw('lower(apps.name) like (?)', ["%{$searching}%"])
                ->orWhereRaw('lower(apps.lastname) like (?)', ["%{$searching}%"])
                ->orWhereRaw('lower(apps.id) like (?)', ["%{$searching}%"])
                ->orWhereRaw('lower(apps.ip) like (?)', ["%{$searching}%"])
                ->orwhereRaw('lower(users.name) like (?)', ["%{$searching}%"])
                ->orWhereRaw('lower(users.lastname) like (?)', ["%{$searching}%"])
                ->orWhereRaw('lower(users.email) like (?)', ["%{$searching}%"]);

        }

//        $builder->select('apps.id', 'apps.user_id', 'apps.lastname', 'apps.name', 'apps.patronymic',
//            'apps.birthday', 'apps.passport_code', 'apps.date_of_issue', 'apps.code_department', 'apps.status',
//            'apps.checking_count');

        $builder->select('apps.*');


        $total = $builder->count();

        $items = $builder->skip(($page - 1) * $limit)->take($limit)->get()->transform(function($item) {
            $app = new App();
            $app->id = $item->id;
            $app->user_id = $item->user_id;
            $app->lastname = $item->lastname;
            $app->name = $item->name;
            $app->patronymic = $item->patronymic;
            $app->birthday = $item->birthday;
            $app->status = $item->status;
            $app->passport_code = $item->passport_code;
            $app->date_of_issue = $item->date_of_issue;
            $app->code_department = $item->code_department;
            $app->checking_count = $item->checking_count;
            $app->checking_date_last = $item->checking_date_last;
            $app->checking_date_next = $item->checking_date_next;
            $app->return_check_quantity = $item->return_check_quantity;
            $app->ip = $item->ip;
            $app->identity = $item->identity;
            $app->created_at = $item->created_at;
            $app->updated_at = $item->updated_at;

            return $app;
        });

        return [
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'items' => $items
        ];
    }
}