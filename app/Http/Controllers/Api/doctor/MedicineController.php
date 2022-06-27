<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineResource;
use App\Models\Medicine;
use App\Models\Mypatient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;



class MedicineController extends Controller
{
    /*public function index($patient){
        $doctorid=Auth::guard('doctor-api')->user()->id;
        $matchThese = ['doctor_id' => $doctorid, 'patient_id' => $patient];
        $check=Mypatient::select('id')->where($matchThese)->get();
        if ($check->isEmpty()){
            return response()->json(['error' => 'not found'], 404);}

    //$prescription = Prescription::create();
        //$prescription->medicne()->createMany();
    }*/
    public function create(Request $request,$patient){

        $condition=['patient_id'=>$patient,'id'=>$request->pres_id];
        $prescription=Prescription::where($condition)->get();
        if ($prescription->isEmpty()){
            return response()->json(['error' => 'not found'], 404);}

        $condition=['patient_id'=>$patient];
        $id=Prescription::select('id')->where($condition)->get();
        $id= $id->pluck('id');
        $oldmedicine=[];
        $i = 0;
        foreach ($id as $item){
            $omedicine=Medicine::select('name')->where(['prescription_id'=>$item])->get();
            $i++;
            foreach ($omedicine as $values){
                $oldmedicine []=$values['name'];
            }
        }

        $response = Http::get('http://rxnav.nlm.nih.gov/REST/rxcui.json?name=',[
                'name' => $request->name,
                'search'=>1
            ]);
        if(!Str::contains($response,'rxnormId'))
            return response()->json(['error' => 'medicine not found'], 404);
        $response=$response['idGroup']['rxnormId'][0];
        $drug_id = Http::get('https://rxnav.nlm.nih.gov/REST/interaction/interaction.json',[
            'rxcui' => $response,
        ]);
        $drug_id->body();

        $contains = Str::contains($drug_id, $oldmedicine);
        if($contains)
            return response()->json(['error' => 'there is drug interaction '], 404);;
        $addmedicine=Medicine::create([
            'name'=>$request->name,
            'prescription_id'=>$request->pres_id,
        ]);
        return new MedicineResource($addmedicine);

    }
    public function update(Request $request,$patient,$medicineid){
        /*$matchThese=['patient_id'=>$patient,'id'=>$request->pres_id];
        $prescription=Prescription::where($matchThese)->get();
        if ($prescription->isEmpty()){
            return response()->json(['error' => 'not found'], 404);}*/

        $reqprescription=Medicine::select('prescription_id')->where(['id'=>$medicineid])->get();
        if ($reqprescription->isEmpty())
            return response()->json(['error' => 'not found'], 404);

        $matchThese=['patient_id'=>$patient,'id'=>$reqprescription->pluck('prescription_id')];
        $check=Prescription::select('id')->where($matchThese)->get();
        if ($check->isEmpty()){
            return response()->json(['error' => 'nottt found'], 404);}

        $id=Prescription::select('id')->where(['patient_id'=>$patient])->get();
        $id= $id->pluck('id');
        $oldmedicine=[];
        $i = 0;
        foreach ($id as $item){
            $omedicine=Medicine::select('name')->where(['prescription_id'=>$item])->get();
            $i++;
            foreach ($omedicine as $values){
                $oldmedicine []=$values['name'];
            }
        }

        $response = Http::get('http://rxnav.nlm.nih.gov/REST/rxcui.json?name=',[
            'name' => $request->name,
            'search'=>1
        ]);
        if(!Str::contains($response,'rxnormId'))
            return response()->json(['error' => 'medicine not found'], 404);
        $response=$response['idGroup']['rxnormId'][0];
        $drug_id = Http::get('https://rxnav.nlm.nih.gov/REST/interaction/interaction.json',[
            'rxcui' => $response,
        ]);
        $drug_id->body() ;

        $contains = Str::contains($drug_id, $oldmedicine);
        if($contains)
            return response()->json(['error' => 'there is medicine interaction '], 404);
        $addmedicine=Medicine::find($medicineid);
        $addmedicine->update([
            'name'=>$request->name,
        ]);
        return response()->json(['message' => 'updated successfully'], 200);

    }
    public function destroy($id)
    {
        $medicine=Medicine::find($id);
        if (!$medicine)
            return response()->json(['error' => 'not found'], 404);
        $medicine->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
