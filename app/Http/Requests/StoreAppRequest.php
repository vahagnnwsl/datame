<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lastname' => 'required|regex:/^[a-zа-яё-]+$/iu',
            'firstname' => 'required|regex:/^[a-zа-яё-]+$/iu',
            'patronymic' => 'required|regex:/^[a-zа-яё-]+$/iu',
            'birthday' => 'required|date_format:d.m.Y',
            'passport_code' => 'required|regex:/^[0-9]{4}\s{1}[0-9]{4}$/',
            'date_of_issue' => 'required|date_format:d.m.Y',
            'code_department' => ''
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            dd($validator);
        });
    }


}
