<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\Constants;
use App\Packages\Providers\Result;
use App\UserDataHistory;
use Hash;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{

    public function getUser(Request $request)
    {
        switch($request->user()->type_user) {
            case Constants::USER_ADMIN:
                return $request->user();
            case Constants::USER_INDIVIDUAL:
                $data = [
                    'name' => $request->user()->name,
                    'lastname' => $request->user()->lastname,
                    'phone' => $request->user()->phone,
                    'to_confirm_data' => null
                ];

                $userDataHistory = $request->user()->userDataHistory()->where('confirmed', false)->first();
                if(!is_null($userDataHistory)) {
                    $data['to_confirm_data'] = [
                        'name' => $userDataHistory->name,
                        'lastname' => $userDataHistory->lastname,
                        'phone' => $userDataHistory->phone,
                    ];
                }
                return $data;
            case Constants::USER_LEGAL:
                $data = [
                    'org' => $request->user()->name,
                    'inn' => $request->user()->inn,
                    'ogrn' => $request->user()->ogrn,
                    'director' => $request->user()->director,
                    'manager' => $request->user()->manager,
                    'phone' => $request->user()->phone,
                    'to_confirm_data' => null
                ];

                $userDataHistory = $request->user()->userDataHistory()->where('confirmed', false)->first();
                if(!is_null($userDataHistory)) {
                    $data['to_confirm_data'] = [
                        'org' => $userDataHistory->name,
                        'inn' => $userDataHistory->inn,
                        'ogrn' => $userDataHistory->ogrn,
                        'director' => $userDataHistory->director,
                        'manager' => $userDataHistory->manager,
                        'phone' => $userDataHistory->phone,
                    ];
                }
                return $data;
                break;
        }


        return $request->user();
    }

    public function postChangePasswordData(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }


        if(!Hash::check($request->get('current_password'), $request->user()->password)) {
            return response()->json($result->setResult("Не верно указан текущий пароль."), 422);
        } else {
            $request->user()->password = Hash::make($request->get('new_password'));
            if($request->user()->save()) {
                $result->setStatusResult(true)->setResult("Пароль успешно изменен.");
            } else {
                return response()->json($result->setResult("Ошибка. Не удалось изменить пароль, обратитесь к администратору."), 422);
            }
        }

        return response()->json($result);
    }

    public function postIndividualData(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:10'],
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }

        $model = UserDataHistory::where('user_id', $request->user()->id)->where('confirmed', false)->first();
        if(is_null($model)) {
            $model = new UserDataHistory;
        }
        $model->user_id = $request->user()->id;
        $model->created_by = $request->user()->id;
        $model->name = $request->get('name');
        $model->lastname = $request->get('lastname');
        $model->phone = add_seven_to_phone($request->get('phone'));
        $model->confirmed = false;


        if($model->save()) {
            $result->setStatusResult(true)->setResult("Изменения вступят в силу после модерации.");
        } else
            $result->setResult("Не удалось сохранить изменения.");

        return response()->json($result);

    }

    public function postLegalData(Request $request)
    {
        $result = new Result;

        $validator = Validator::make($request->all(), [
            'org' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'max:12'],
            'ogrn' => ['required', 'string', 'max:255'],
            'director' => ['required', 'string', 'max:255'],
            'manager' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:10'],
        ]);

        if($validator->fails()) {
            return response()->json($result->setResult($validator->messages()->first()), 422);
        }

        $model = UserDataHistory::where('user_id', $request->user()->id)->where('confirmed', false)->first();
        if(is_null($model)) {
            $model = new UserDataHistory;
        }
        $model->user_id = $request->user()->id;
        $model->created_by = $request->user()->id;
        $model->name = $request->get('org');
        $model->inn = $request->get('inn');
        $model->ogrn = $request->get('ogrn');
        $model->director = $request->get('director');
        $model->manager = $request->get('manager');
        $model->phone = add_seven_to_phone($request->get('phone'));

        if($model->save()) {
            $result->setStatusResult(true)->setResult("Изменения вступят в силу после модерации.");
        } else
            $result->setResult("Не удалось сохранить изменения.");

        return response()->json($result);
    }

}