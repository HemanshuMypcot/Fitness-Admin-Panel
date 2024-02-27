<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $duration_time = $this->time_end->diff($this->time_start)->format('%h:%I');
        $data = [
            'id' =>$this->id,
            'course_name' => $this->course_name,
            'time_start' => date('g.i a', strtotime($this->time_start)),
            'instructor_name' => $this->instructor->name,
            'duration' =>  $duration_time.' hrs',
        ];

        return $data;
    }
}
