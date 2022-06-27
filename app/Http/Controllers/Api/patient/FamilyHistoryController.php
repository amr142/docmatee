<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyHistoryResource;
use App\Models\FamilyHistory;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyHistoryController extends Controller
{
    public function index()
    {
        $FamilyHistory= Auth::guard('patient-api')->user()->familyhistory()->get();
        if (!$FamilyHistory)
            return response()->json(['error' => 'not found'], 404);
        return FamilyHistoryResource::collection($FamilyHistory);
    }

    public function create(Request $request)
    {
        $id=Auth::guard('patient-api')->user()->id;
        $FamilyHistory =FamilyHistory::create([
            'disease'=>$request->disease,
            'realation'=>$request->realation,
            'patient_id'=>$id,
        ]);
        return new FamilyHistoryResource($FamilyHistory);
    }

    public function show($id)
    {
        $FamilyHistory=Auth::guard('patient-api')->user()->familyhistory()->find($id);
        if (!$FamilyHistory)
            return response()->json(['error' => 'not found'], 404);
        return new FamilyHistoryResource($FamilyHistory);
    }

    public function update(Request $request, $id)
    {
        $FamilyHistory=Auth::guard('patient-api')->user()->familyhistory()->find($id);
        if (!$FamilyHistory)
            return response()->json(['error' => 'not found'], 404);
        $FamilyHistory->update([
            'disease'=>$request->disease,
            'realation'=>$request->realation,
        ]);
        return new FamilyHistoryResource($FamilyHistory);
    }

    public function destroy($id)
    {
        $FamilyHistory=Auth::guard('patient-api')->user()->familyhistory()->find($id);
        if (!$FamilyHistory)
            return response()->json(['error' => 'not found'], 404);
        $FamilyHistory->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
