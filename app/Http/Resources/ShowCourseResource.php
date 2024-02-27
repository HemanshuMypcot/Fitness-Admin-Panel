<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use Illuminate\Support\Facades\Session;

class ShowCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $average_rating = "";
        if (!empty($this->courseRating->toArray())) {
            $ratings = array_column($this->courseRating->toArray(), "rating");
            $average_rating = array_sum($ratings)/count($ratings);# code...
        }
//        $days_open_full = array_keys(array_filter($this->opens_at, function ($status) {
//            return $status === "on";
//        }));
        // Extracting the first 3 letters of each day
//        $days_open = array_map(function ($day) {
//            return \Carbon\Carbon::parse($day)->format('D');
//        }, $days_open_full);
        $format = Session::get('format');
        $registrationStartDateTime = Carbon::parse($this->registration_start);
        $registrationEndDateTime = Carbon::parse($this->registration_end);
        $currentDateTime = Carbon::now()->startOfDay();
        $isBetween = $currentDateTime->between($registrationStartDateTime, $registrationEndDateTime);
        $courseStartDate = Carbon::parse($this->date_start)->format('Y-m-d');
        $registrationEndDate = Carbon::parse($this->registration_end)->format('Y-m-d');
        if ($isBetween && ($courseStartDate == $registrationEndDate)) {
            $targetDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $dateStart = Carbon::parse($this->date_start)->format('Y-m-d');
            $isBetween = ($dateStart.' '.$this->time_start >= $targetDateTime);
        }
        $startOfDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endOfDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        $targetDateTime = Carbon::now()->format('Y-m-d H:i:s');
        $data = [
            'id' =>$this->id,
            'course_name' => $this->course_name,
            'course_details' => $this->course_details,
            'additional_requirement' => $this->additional_requirement,
            'instructor_name' => $this->instructor->name,
            'course_rating' => $average_rating,
            'course_image' => $this->image_url ?? '',
            'opens_at' => $this->opens_at,
            'course_type' => $this->type,
            'date_start' => Carbon::parse($this->date_start)->translatedFormat($format),
            'date_end' => Carbon::parse($this->date_end)->translatedFormat($format),
            'time_start' => date('h:i a', strtotime($this->time_start)),
            'time_end' => date('h:i a', strtotime($this->time_end)),
            'duration' => $this->duration_time ?? '',
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'amount' => $this->total,
            "instructor_courses" => CourseResource::collection(Course::where('instructor_id', $this->instructor_id)->where('id', '!=', $this->id)->where('registration_start', '<=', $startOfDay)->where('registration_end', '>=', $endOfDay)->whereRaw("(CONCAT(date_start, ' ', time_start) >= ?)", [$targetDateTime])->get()),
            "related_courses" => CourseResource::collection(Course::where('course_category_id', $this->course_category_id)->where('id', '!=', $this->id)->where('registration_start', '<=', $startOfDay)->whereRaw("(CONCAT(date_start, ' ', time_start) >= ?)", [$targetDateTime])->where('registration_end', '>=', $endOfDay)->get()),
            "is_enroll" => $isBetween
        ];
        if (!empty( $data['course_details'])){
            $data['course_details'] = str_replace("<br/>", "\n", $data['course_details']);
        }
        if (!empty( $data['additional_requirement'])){
            $data['additional_requirement'] = str_replace("<br/>", "\n", $data['additional_requirement']);
        }
        return $data;
    }
}
