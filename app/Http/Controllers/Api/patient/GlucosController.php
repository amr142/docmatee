<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlucosResource;
use App\Models\Bloodglucos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlucosController extends Controller
{
    public function index()
    {
        $glucos= Auth::guard('patient-api')->user()->glucos()->get();
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        return GlucosResource::collection($glucos);
    }

    public function create(Request $request)
    {
        $request->validate([
            'glucos_result'=>'required',
            'glucos_type'=>'required',
        ]);
        $id=Auth::guard('patient-api')->user()->id;
        $glucos =Bloodglucos::create([
            'glucos_result'=>$request->glucos_result,
            'glucos_type'=>$request->glucos_type,
            'time'=>$request->time,
            'date'=>$request->date,
            'patient_id'=>$id,
        ]);
        return new GlucosResource($glucos);

    }

    public function show($id)
    {
        $glucos=Auth::guard('patient-api')->user()->glucos()->find($id);
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        return new GlucosResource($glucos);
    }

    public function update(Request $request, $id)
    {
        $glucos=Auth::guard('patient-api')->user()->glucos()->find($id);
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        $glucos->update([
            'glucos_result'=>$request->glucos_result,
            'glucos_type'=>$request->glucos_type,
            'time'=>$request->time,
            'date'=>$request->date,
            ]);
        return new GlucosResource($glucos);
    }

    public function destroy($id)
    {
        $glucos=Auth::guard('patient-api')->user()->glucos()->find($id);
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        $glucos->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
