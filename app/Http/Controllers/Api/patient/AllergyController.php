<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyResource;
use App\Models\Allergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllergyController extends Controller
{
    public function index()
    {
        $allergy= Auth::guard('patient-api')->user()->allergy()->get();
        if (!$allergy)
            return response()->json(['error' => 'not found'], 404);
        return AllergyResource::collection($allergy);
    }

    public function create(Request $request)
    {

        $id=Auth::guard('patient-api')->user()->id;

        $allergy =Allergy::create([
            'allergy'=>$request->name,
            'patient_id'=>$id,
        ]);
        return new AllergyResource($allergy);

    }

    public function show($id)
    {
        $allergy=Auth::guard('patient-api')->user()->allergy()->find($id);
        if (!$allergy)
            return response()->json(['error' => 'not found'], 404);
        return new AllergyResource($allergy);
    }

    public function update(Request $request, $id)
    {
        $allergy=Auth::guard('patient-api')->user()->allergy()->find($id);
        if (!$allergy)
            return response()->json(['error' => 'not found'], 404);
        $allergy->update([
            'allergy'=>$request->name,
        ]);
        return new AllergyResource($allergy);
    }

    public function destroy($id)
    {
        $allergy=Auth::guard('patient-api')->user()->lab()->find($id);
        if (!$allergy)
            return response()->json(['error' => 'not found'], 404);
        $allergy->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
