<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $format = Session::get('format');
        $data = [
            'id' =>$this->id,
            'date_start' => Carbon::parse($this->date_start)->translatedFormat($format),
            'time_start' => date('h:i a', strtotime($this->time_start)),
            'course_name' => $this->course_name,
            'course_image' => $this->image_url ?? ''
        ];

        return $data;
    }
}
