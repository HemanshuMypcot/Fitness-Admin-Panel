<?php

namespace App\Http\Requests;

use App\Models\HomeCollection;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateHomeCollectionRequest extends FormRequest
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
        $ruleData = [
            'collection_type' => 'required|in:Single,Course',
            'sequence'        => 'required|integer',
//            'display_in_column'        => 'required|integer',
        ];
        $collectionType = $this->input('collection_type');
        if ($collectionType == HomeCollection::SINGLE) {
            $ruleData['single_image'] = 'required|mimes:jpeg,jpg,png,gif';
        }
        if ($collectionType == HomeCollection::COURSE) {
            $ruleData['time_start'] = 'required';
            $ruleData['time_end'] = 'required';
        }
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
}
