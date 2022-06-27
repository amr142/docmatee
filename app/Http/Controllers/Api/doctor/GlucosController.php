<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlucosResource;
use App\Models\Mypatient;
use App\Models\Bloodglucos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GlucosController extends Controller
{

    public function index($patient)
    {
        $glucos=Bloodglucos::where('patient_id','=',$patient)->get();
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        return GlucosResource::collection($glucos);


    }

    public function create(Request $request ,$patient)
    {
        $request->validate([
            'glucos_result'=>'required',
            'glucos_type'=>'required',
        ]);

        $glucos =Bloodglucos::create([
            'glucos_result'=>$request->glucos_result,
            'glucos_type'=>$request->glucos_type,
            'time'=>$request->time,
            'date'=>$request->date,
            'patient_id'=>$patient,
        ]);
        return new GlucosResource($glucos);

    }

    public function show($patient,$id)
    {

        $condition=['patient_id'=>$patient,'id'=>$id];
        $glucos=Bloodglucos::where($condition)->get();
        if ($glucos->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new GlucosResource($glucos[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $glucos=Bloodglucos::where($condition);
        if (!$glucos)
            return response()->json(['error' => 'glucos not found'], 404);
        $glucos=$glucos->update([
            'glucos_result'=>$request->glucos_result,
            'glucos_type'=>$request->glucos_type,
            'time'=>$request->time,
            'date'=>$request->date,
            ]);
        $glucos=Bloodglucos::find($id);
        if (!$glucos)
            return response()->json(['error' => 'glucos not found'], 404);
        return new GlucosResource($glucos);
    }

    public function destroy($patient,$id)
    {
        $glucos=Bloodglucos::find($id);
        if (!$glucos)
            return response()->json(['error' => 'not found'], 404);
        $glucos->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
