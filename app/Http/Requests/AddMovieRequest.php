<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class AddMovieRequest extends FormRequest
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
            'category' => 'required',
            'name' =>'required',
            'season'=>'required|integer',
            'yt_link'=>'required',
            'image'   => "required|mimes:jpeg,jpg,png,gif|max:". $maxFileSize
        ];
        if ($this->input('category') =='Movie'){
            $rules['season'] = 'nullable';
        }
        if ($this->input('category') =='Web Series'){
            $rules['yt_link'] = 'nullable';
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'action.required' => 'Please select at least one genre.',
        ];
    }
}
