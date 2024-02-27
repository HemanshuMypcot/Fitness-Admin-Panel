<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateStaffRequest extends FormRequest
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
        $ruleData = Admin::$rules;
        $ruleData['password'] = 'required|string|min:8|max:28';
        $ruleData['email'] = 'required|email|unique:admins,email,NULL,id,deleted_at,NULL';
        $ruleData['phone'] = 'required|numeric|unique:admins,phone,NULL,id,deleted_at,NULL|digits:10';
        $ruleData['nick_name'] = 'required|max:3';
        $ruleData['admin_name'] = 'required|max:50';

        return $ruleData;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => 0,
            'message' => $validator->errors()->all()
        ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    protected function prepareForValidation()
    {
        // Convert the 'email' field to lowercase before validation
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }
}
