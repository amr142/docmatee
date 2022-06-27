<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'email'=>$this->email,
            'date'=>$this->date,
            'phone'=>$this->phone,
            'address'=>$this->address,
            'blood_type'=>$this->blood_type,
            'weight'=>$this->weight,
            'height'=>$this->height,
            'image'=>$this->image,

        ];
    }
}
