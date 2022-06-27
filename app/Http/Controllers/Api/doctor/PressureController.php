<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\PressureResource;
use App\Models\Bloodpressure;
use App\Models\Mypatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PressureController extends Controller
{

    public function index($patient)
    {
        $pressure=Bloodpressure::where('patient_id','=',$patient)->get();
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        return PressureResource::collection($pressure);


    }

    public function create(Request $request ,$patient)
    {
        $request->validate([
            'systolic_pressure'=>'required',
            'diastolic_pressure'=>'required',

        ]);

        $pressure =Bloodpressure::create([
            'systolic_pressure'=>$request->systolic_pressure,
            'diastolic_pressure'=>$request->diastolic_pressure,
            'time'=>$request->time,
            'date'=>$request->date,
            'patient_id'=>$patient,
        ]);
        return new PressureResource($pressure);

    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $pressure=Bloodpressure::where($condition)->get();
        if ($pressure->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new PressureResource($pressure[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $pressure=Bloodpressure::where($condition);
        if (!$pressure)
            return response()->json(['error' => 'pressure not found'], 404);
        $pressure=$pressure->update([
            'systolic_pressure'=>$request->systolic_pressure,
            'diastolic_pressure'=>$request->diastolic_pressure,
            'time'=>$request->time,
            'date'=>$request->date,
        ]);
        $pressure=Bloodpressure::find($id);
        if (!$pressure)
            return response()->json(['error' => 'pressure not found'], 404);
        return new PressureResource($pressure);
    }

    public function destroy($patient,$id)
    {
        $pressure=Bloodpressure::find($id);
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        $pressure->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
