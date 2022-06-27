<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PressureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'systolic_pressure'=>$this->systolic_pressure,
            'diastolic_pressure'=>$this->diastolic_pressure,
            'time'=>$this->time,
            'date'=>$this->date,
            ];
    }
}
