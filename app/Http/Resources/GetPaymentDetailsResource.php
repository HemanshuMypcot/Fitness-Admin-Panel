<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use Illuminate\Support\Facades\Session;

class GetPaymentDetailsResource extends JsonResource
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
        $average_rating = "";
        if (!empty($this->courseRating->toArray())) {
            $ratings = array_column($this->courseRating->toArray(), "rating");
            $average_rating = array_sum($ratings)/count($ratings);# code...
        }
        $data = [
            'id' =>$this->id,
            'course_name' => $this->course_name,
            'instructor_name' => $this->instructor->name,
            'course_rating' => $average_rating,
            'course_image' => $this->image_url ?? '',
            'date_start' => Carbon::parse($this->date_start)->translatedFormat($format),
            'time_start' => date('h:i a', strtotime($this->time_start)),
            'time_end' => date('h:i a', strtotime($this->time_end)),
            'duration' => $this->time_end->diffInMinutes($this->time_start). ' minutes',
            'address' => $this->address,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'service_charge' => $this->service_charge,
            'discount' => $this->discount,
            'service_charge_amount' => $this->service_charge_amount ?? 0,
            'tax_amount' => $this->tax_amount ?? 0,
            'discount_amount' => $this->discount_amount ?? 0,
            'total' => $this->total,
            'payment_applicable' => $this->total != 0
        ];

        return $data;
    }
}
