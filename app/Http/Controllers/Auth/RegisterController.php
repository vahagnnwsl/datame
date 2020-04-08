<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Packages\Constants;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = route('apps');
    }

    /**
     * Форма регистраци для физического лица
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndividualRegistrationForm()
    {
        return view('auth.register-individual');
    }

    /**
     * Форма регистрации для юридического лица
     *
     * @return \Illuminate\Http\Response
     */
    public function showLegalRegistrationForm()
    {
        return view('auth.register-legal');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if(isset($data['phone'])) {
            $data['phone'] = clear_phone($data['phone']);
        }

        //регается физ. лицо
        if($data['type_user'] == Constants::USER_INDIVIDUAL) {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-]+$/ui'],
                'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-]+$/ui'],
                'phone' => ['required', 'digits:11'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'type_user' => ['required', 'digits:1'],
                'oferta_confirmed' => ['required']
            ]);
        } else {
            return Validator::make($data, [
                'org' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
                'inn' => ['required', 'numeric',  'regex:/^[0-9]{10}$/i'],
                'ogrn' => ['required', 'numeric', 'regex:/^[0-9]{13}$/i'],
                'director' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
                'manager' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Zа-яА-Я-\s]+$/ui'],
                'phone' => ['required', 'digits:11'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'type_user' => ['required', 'digits:1'],
                'oferta_confirmed' => ['required']
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(isset($data['phone'])) {
            $data['phone'] = clear_phone($data['phone']);
        }

        $password = Hash::make($data['password']);

        if($data['type_user'] == Constants::USER_INDIVIDUAL) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone'],
                'type_user' => Constants::USER_INDIVIDUAL,
                'password' => $password,
                'oferta_confirmed' => true
            ]);
        } else {
            //регается юр. лицо
            return User::create([
                'name' => $data['org'],
                'inn' => $data['inn'],
                'ogrn' => $data['ogrn'],
                'director' => $data['director'],
                'manager' => $data['manager'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'type_user' => Constants::USER_LEGAL,
                'password' => $password,
                'oferta_confirmed' => true
            ]);
        }
    }
}
