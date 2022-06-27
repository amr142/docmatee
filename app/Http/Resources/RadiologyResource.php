<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RadiologyResource extends JsonResource
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
            'name'=>$this->name,
            'location'=>$this->location,
            'type'=>$this->type,
            'date'=>$this->date,
            'image'=>$this->image,
        ];
    }
}
