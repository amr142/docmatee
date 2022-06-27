<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyResource;
use App\Models\Mypatient;
use App\Models\Allergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AllergyController extends Controller
{

    public function index($patient)
    {
        /*$doctorid=Auth::guard('doctor-api')->user()->id;
        $matchThese = ['doctor_id' => $doctorid, 'patient_id' => $patient];
        $check=Mypatient::select('id')->where($matchThese)->get();
        if ($check->isEmpty()){
            return response()->json(['error' => 'not found'], 404);}*/
        $Allergy=Allergy::where('patient_id','=',$patient)->get();
        if (!$Allergy)
            return response()->json(['error' => 'not found'], 404);
        return AllergyResource::collection($Allergy);


    }

    public function create(Request $request ,$patient)
    {
        $Allergy =Allergy::create([
            'allergy'=>$request->name,
            'patient_id'=>$patient,
        ]);
        return new AllergyResource($Allergy);

    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $Allergy=Allergy::where($condition)->get();
        if ($Allergy->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new AllergyResource($Allergy[0]);
    }

    public function update($patient,Request $request,$id)
    {

        $condition=['patient_id'=>$patient,'id'=>$id];
        $Allergy=Allergy::where($condition);
        if (!$Allergy)
            return response()->json(['error' => 'Allergy not found'], 404);
        $Allergy=$Allergy->update([
            'allergy'=>$request->name,
        ]);
        $Allergy=Allergy::find($id);
        if (!$Allergy)
            return response()->json(['error' => 'Allergy not found'], 404);
        return new AllergyResource($Allergy);
    }

    public function destroy($patient,$id)
    {

        $Allergy=Allergy::find($id);
        if (!$Allergy)
            return response()->json(['error' => 'not found'], 404);
        $Allergy->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
