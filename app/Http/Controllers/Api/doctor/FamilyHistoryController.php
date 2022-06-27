<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyHistoryResource;
use App\Models\Mypatient;
use App\Models\FamilyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FamilyHistoryController extends Controller
{

    public function index($patient)
    {
        $History=FamilyHistory::where('patient_id','=',$patient)->get();
        if (!$History)
            return response()->json(['error' => 'not found'], 404);
        return FamilyHistoryResource::collection($History);


    }

    public function create(Request $request ,$patient)
    {

        $request->validate([
            'disease'=>'required',
            'realation'=>'required',
        ]);
        $History =FamilyHistory::create([
            'disease'=>$request->disease,
            'realation'=>$request->realation,
            'patient_id'=>$patient,
        ]);
        return new FamilyHistoryResource($History);

    }

    public function show($patient,$id)
    {

        $condition=['patient_id'=>$patient,'id'=>$id];
        $History=FamilyHistory::where($condition)->get();
        if ($History->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new FamilyHistoryResource($History[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $History=FamilyHistory::where($condition);
        if (!$History)
            return response()->json(['error' => 'family history not found'], 404);
        $History=$History->update([
            'disease'=>$request->disease,
            'realation'=>$request->realation,
        ]);
        $History=FamilyHistory::find($id);
        if (!$History)
            return response()->json(['error' => 'FamilyHistory not found'], 404);
        return new FamilyHistoryResource($History);
    }

    public function destroy($patient,$id)
    {

        $History=FamilyHistory::find($id);
        if (!$History)
            return response()->json(['error' => 'not found'], 404);
        $History->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
