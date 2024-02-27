<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        unset($data['translations'],$data['updated_by'],$data['updated_at'],$data['created_by'],$data['status'],$data['created_at'],$data['media']);   
        if (!empty($this->description)){
            $data['description'] = str_replace("<br/>", "\n", $this->description);
        }

        return $data;
    }
}
