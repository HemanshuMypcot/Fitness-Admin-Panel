<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AddLocationRequest extends FormRequest
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
        $validation = [
            'name'  => 'required',
        ];
        
        if (translationMaxLengthValidation($this->input('name'), 50)){
            $validation['name'] = 'required|max:50';
        }
        return $validation;
    }

    protected function failedValidation(Validator $validator)
    {
        $messages = $validator->errors()->all();

        // Filter duplicate messages.
        $filteredMessages = [];
        foreach ($messages as $message) {
            if (!in_array($message, $filteredMessages)) {
                $filteredMessages[] = $message;
            }
        }

        // Show the validation messages to the user.
        $response = response()->json([
            'success' => 0,
            'message' => $filteredMessages,
        ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

}
