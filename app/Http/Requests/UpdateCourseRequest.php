<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateCourseRequest extends FormRequest
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
        $maxFileSize = config('global.sizes.MAX_COURSE_UPLOAD_FILE_SIZE');
    $rules = [
        'course_id' => 'required',
        'course_category_id' => 'required|exists:course_categories,id',
        'instructor_id' => 'required|exists:instructors,id',
        'date_start' => 'required|date',
        'date_end' => 'required|date|after_or_equal:date_start',
        'time_start' => 'required',
        'time_end' => 'required|after_or_equal:time_start',
        'capacity' => 'required|integer',
        'sequence' => 'required|integer',
        'amount' => 'required|numeric',
        'registration_start' => 'required|date|before_or_equal:date_start',
        'registration_end' => 'required|date|after_or_equal:registration_start|before_or_equal:date_start',
        'type' => 'required|in:one_day,recurring',
        'image'   => "mimes:jpeg,jpg,png,gif|max:". $maxFileSize
    ];
        if ($this->input('type') == 'one_day') {
            $rules['date_end'] = 'nullable|date';
        }
    if (($this->input('type') == 'recurring') && empty($this->input('monday')) && empty($this->input('tuesday')) && empty($this->input('wednesday')) && empty($this->input('thursday')) && empty($this->input('friday')) && empty($this->input('saturday')) && empty($this->input('sunday'))) {
        $rules['monday'] = 'required';
    }
    if (translationMaxLengthValidation($this->input('course_name'), 50)){
        $rules['course_name'] = 'required|max:50';
    }

    return $rules;
    }

    public function messages()
    {
        return [
            'monday.required' => 'Please select at least one day.',
        ];
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
        $messages = $validator->errors()->all();

        $filteredMessages = [];
        foreach ($messages as $message) {
            if (!in_array($message, $filteredMessages)) {
                $filteredMessages[] = $message;
            }
        }

        $response = response()->json([
            'success' => 0,
            'message' => $filteredMessages,
        ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
