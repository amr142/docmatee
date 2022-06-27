<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PressureResource;
use App\Models\Bloodpressure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PressureController extends Controller
{
    public function index()
    {
        $pressure= Auth::guard('patient-api')->user()->pressure()->get();
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        return PressureResource::collection($pressure);
    }

    public function create(Request $request)
    {
        $request->validate([
            'systolic_pressure'=>'required',
            'diastolic_pressure'=>'required',

        ]);
        $id=Auth::guard('patient-api')->user()->id;
        $pressure =Bloodpressure::create([
            'systolic_pressure'=>$request->systolic_pressure,
            'diastolic_pressure'=>$request->diastolic_pressure,
            'time'=>$request->time,
            'date'=>$request->date,
            'patient_id'=>$id,
        ]);
        return new PressureResource($pressure);

    }

    public function show($id)
    {
        $pressure=Auth::guard('patient-api')->user()->pressure()->find($id);
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        return new PressureResource($pressure);
    }

    public function update(Request $request, $id)
    {
        $pressure=Auth::guard('patient-api')->user()->pressure()->find($id);
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        $pressure->update([
            'systolic_pressure'=>$request->systolic_pressure,
            'diastolic_pressure'=>$request->diastolic_pressure,
            'time'=>$request->time,
            'date'=>$request->date,
        ]);
        return new PressureResource($pressure);
    }

    public function destroy($id)
    {
        $pressure=Auth::guard('patient-api')->user()->pressure()->find($id);
        if (!$pressure)
            return response()->json(['error' => 'not found'], 404);
        $pressure->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
