<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ShowBookedCourseResource extends JsonResource
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
        $result = parent::toArray($request);
       
        $result['details']['start_date'] = Carbon::parse($result['details']['start_date'])->translatedFormat($format);
        $result['details']['end_date'] = Carbon::parse($result['details']['end_date'])->translatedFormat($format);
        $result['time_start'] = date('h:i a', strtotime($this->course->time_start));
        $result['duration'] = $this->course->duration_time;
        $result['address'] = $this->course->address;
        $result['image'] = $this->course->image_url ?? '';
        $result['course_type'] = $this->course->type ?? '';
        $result['enrolles_on'] = Carbon::parse($this->created_at)->translatedFormat($format);
        $result['completes_on'] = $result['details']['end_date'];
        unset($result['course']);
        
        return $result;
    }
}
