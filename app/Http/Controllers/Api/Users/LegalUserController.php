<?php

namespace App\Http\Controllers\Api\Users;


use App\Http\Controllers\Api\PaginationResult;
use App\Http\Controllers\Controller;
use App\Packages\Constants;
use App\Packages\Providers\Result;
use App\User;
use App\UserDataHistory;
use Hash;
use Illuminate\Http\Request;
use Validator;

/**
 *
 * Управление пользователем юридического лица
 *
 * Class LegalUserController
 * @package App\Http\Controllers\Api\Users
 */
class LegalUserController extends Controller
{

    /**
     * Регистрация пользователя
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'org' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'inn' => ['required', 'numeric', 'regex:/^[0-9]{10}$/i'],
            'ogrn' => ['required', 'numeric', 'regex:/^[0-9]{13}$/i'],
            'director' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'manager' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'digits:11'],
            'check_quantity' => ['nullable', 'integer'],
            'date_service' => ['nullable', 'date_format:d.m.Y'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }

        $user = User::create([
            'name' => trim($request->get('org')),
            'email' => $request->get('email'),
            'inn' => $request->get('inn'),
            'ogrn' => $request->get('ogrn'),
            'director' => trim($request->get('director')),
            'manager' => trim($request->get('manager')),
            'phone' => $request->get('phone'),
            'type_user' => Constants::USER_LEGAL,
            'confirmed' => true,
            'date_service' => !is_null($request->get('date_service')) ? dt_parse($request->get('date_service')) : null,
            'check_quantity' => !is_null($request->get('check_quantity')) ? $request->get('check_quantity') : 0,
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json($result->setStatusResult(true)->setResult($this->transformUser($user)));
    }

    /**
     * Редактирование данных пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEdit(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->get('id')],
            'org' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'inn' => ['required', 'numeric', 'regex:/^[0-9]{10}$/i'],
            'ogrn' => ['required', 'numeric', 'regex:/^[0-9]{13}$/i'],
            'director' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'manager' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
            'phone' => ['required', 'digits:11'],
            'confirmed' => ['nullable'],
            'check_quantity' => ['nullable', 'integer'],
            'date_service' => ['nullable', 'date_format:d.m.Y'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }

        $user = User::find($request->get('id'));
        if(is_null($user))
            return response()->json($result->setResult("Такого пользователя не существует"), 404);

        $user->name = $request->get('org');
        $user->inn = $request->get('inn');
        $user->ogrn = $request->get('ogrn');
        $user->manager = $request->get('manager');
        $user->director = $request->get('director');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->date_service = !is_null($request->get('date_service')) ? dt_parse($request->get('date_service')) : null;
        $user->check_quantity = !is_null($request->get('check_quantity')) ? $request->get('check_quantity') : 0;
        $user->confirmed = $request->get('confirmed');

        if(!empty($request->get('password'))) {
            $user->password = Hash::make($request->get('password'));
        }

        $model = new UserDataHistory;
        $model->created_by = $request->user()->id;
        $model->confirmed_by = $request->user()->id;
        $model->name = $user->name;
        $model->inn = $user->inn;
        $model->ogrn = $user->ogrn;
        $model->manager = $user->manager;
        $model->director = $user->director;
        $model->phone = $user->phone;
        $model->date_service = $user->date_service;
        $model->check_quantity = $user->check_quantity;
        $model->confirmed = true;

        $user->userDataHistory()->save($model);

        $result->setStatusResult($user->save());

        return response()->json($result->setResult($this->transformUser($user, true)));

    }

    /**
     * Возвращает список физических лиц с пагинацей
     *
     * @param Request $request
     * @param int $page
     * @param int $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request, $page = 1, $limit = 10)
    {
        /** @var User $builder */
        $builder = User::where('type_user', Constants::USER_LEGAL)->with(['userDataHistory' => function($query) {
            $query->where('confirmed', 0);
        }])->orderByDesc('id');

        $searching = $request->get('searching');
        if(!empty($searching)) {
            $builder = $builder->where(function($query) use ($searching) {
                return $query->where('org', 'like', "%{$searching}%")
                    ->orWhere('inn', 'like', "%{$searching}%")
                    ->orWhere('ogrn', 'like', "%{$searching}%")
                    ->orWhere('email', 'like', "%{$searching}%")
                    ->orWhere('phone', 'like', "%{$searching}%")
                    ->orWhere('lastname', 'like', "%{$searching}%");
            });
        }

        $total = $builder->count();

        $users = $builder->skip(($page - 1) * $limit)->take($limit)->orderBy('confirmed')->get()->transform(function(User $user) {
            return $this->transformUser($user, true);
        });

        return response()->json(
            (new PaginationResult($page, $limit, $total))->setItems($users->toArray())->toArray()
        );
    }

    /**
     * Сохранить изменения в пользователе
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postConfirm(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer']
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }

        $history = UserDataHistory::find($request->get('id'));
        if(is_null($history))
            return response()->json($result->setResult("Ошибка. Изменение не существует"), 404);

        /** @var User $user */
        $user = $history->user()->first();
        $user->name = $history->name;
        $user->lastname = $history->lastname;
        $user->phone = $history->phone;

        if($user->save()) {
            $history->confirmed_by = $request->user()->id;
            $history->confirmed = 1;
            $history->save();
        };

        return response()->json($result->setStatusResult(true)->setResult($this->transformUser($user, true)));
    }

    /**
     * Преобразование пользователя
     *
     * @param User $user
     * @param bool $withHistory
     * @return array
     */
    private function transformUser(User $user, $withHistory = false)
    {
        $data = [
            'id' => $user->id,
            'org' => $user->name,
            'inn' => $user->inn,
            'ogrn' => $user->ogrn,
            'manager' => $user->manager,
            'director' => $user->director,
            'email' => $user->email,
            'phone' => $user->phone,
            'type_user' => $user->type_user,
            'date_service' => !is_null($user->date_service) ? format_date($user->date_service) : null,
            'check_quantity' => $user->check_quantity,
            'confirmed' => (int)$user->confirmed,
            'created_at' => format_date($user->created_at),
            'to_confirm_data' => null
        ];

        if($withHistory && $user->userDataHistory->isNotEmpty()) {
            /** @var UserDataHistory $dt */
            $dt = $user->userDataHistory()->where('confirmed', 0)->first();
            if(!is_null($dt)) {
                $data['to_confirm_data'] = [
                    'id' => $dt->id,
                    'org' => $dt->name,
                    'inn' => $dt->inn,
                    'ogrn' => $dt->ogrn,
                    'manager' => $dt->manager,
                    'director' => $dt->director,
                    'phone' => $dt->phone,
                ];
            }
        }
        return $data;
    }
}