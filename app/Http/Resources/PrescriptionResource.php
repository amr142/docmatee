<?php

namespace App\Http\Resources;

use App\Models\Doctor;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $doctor=Doctor::select('id','name')->where(['id'=>$this->doctor_id])->get();
        return [
                'id'=>$this->id,
                'summary'=>$this->summary,
                'notes'=>$this->notes,
                'date'=>$this->date,
                'Prescription_photo'=>$this->Prescription_photo,
                'doctor_id'=>$doctor[0]['id'],
                'doctor_name'=>$doctor[0]['name'],
                'medicine'=>MedicineResource::collection($this->medicine),
        ];

    }
}
