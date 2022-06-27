<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GlucosResource extends JsonResource
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
            'glucos_result'=>$this->glucos_result,
            'glucos_type'=>$this->glucos_type,
            'time'=>$this->time,
            'date'=>$this->date,
        ];
    }
}
