<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrescriptionResource;
use App\Models\Mypatient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PrescriptionController extends Controller
{

    public function index($patient)
    {
        $Prescription=Prescription::where('patient_id','=',$patient)->get();
        if (!$Prescription)
            return response()->json(['error' => 'not found'], 404);
        return PrescriptionResource::collection($Prescription);


    }

    public function create(Request $request ,$patient)
    {
        $doctorid=Auth::guard('doctor-api')->user()->id;
        $matchThese=['Prescription_photo'=>null, 'notes'=>null,'summary'=>null,'patient_id'=>$patient];
        $checknull=Prescription::select('id')->where($matchThese)->get();
        if($checknull->isEmpty()) {
            $Prescription =Prescription::create([
                'doctor_id'=>$doctorid,
                'patient_id'=>$patient,
            ]);
            $matchThese=['id'=>$Prescription['id']];
            $id=Prescription::select('id')->where($matchThese)->get();
            return response()->json(['data' => $id],200);
        }

        return response()->json(['data' => $checknull],200);

    }

    public function show($patient,$id)
    {

        $condition=['patient_id'=>$patient,'id'=>$id];
        $Prescription=Prescription::where($condition)->get();
        if ($Prescription->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new PrescriptionResource($Prescription[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $Prescription=Prescription::where($condition);
        if (!$Prescription)
            return response()->json(['error' => 'Prescription not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $Prescription->update([
                'summary'=>$request->summary,
                'notes'=>$request->notes,
                'Prescription_photo'=>$result,
                'date'=>$request->date,
            ]);
            $Prescription=Prescription::find($id);
            return new PrescriptionResource($Prescription);
        }
        $Prescription->update([
            'summary'=>$request->summary,
            'notes'=>$request->notes,
            'Prescription_photo'=>'https://i.ibb.co/n7yGWqz/mm.png',
            'date'=>$request->date,
        ]);
        $Prescription=Prescription::find($id);
        return new PrescriptionResource($Prescription);
    }

    public function destroy($patient,$id)
    {
        /*$doctorid=Auth::guard('doctor-api')->user()->id;
        $matchThese = ['doctor_id' => $doctorid, 'patient_id' => $patient];
        $check=Mypatient::select('id')->where($matchThese)->get();
        if ($check->isEmpty()){
            return response()->json(['error' => 'not found'], 404);}*/
        $Prescription=Prescription::find($id);
        if (!$Prescription)
            return response()->json(['error' => 'not found'], 404);
        $Prescription->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
