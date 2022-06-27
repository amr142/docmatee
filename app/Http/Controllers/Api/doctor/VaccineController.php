<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\VaccineResource;
use App\Models\Mypatient;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VaccineController extends Controller
{

    public function index($patient)
    {
        $vaccine=Vaccine::where('patient_id','=',$patient)->get();
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);
        return VaccineResource::collection($vaccine);


    }

    public function create(Request $request ,$patient)
    {

        if($request->hasFile('image')){
            $file = time() .'.'.request()->file('image')->extension();
            request ()->file('image')->storeAs('public',$file);
            $result="http://docmate.herokuapp.com/storage/".$file;
            $vaccine =Vaccine::create([
                'name'=>$request->name,
                'date'=>$request->date,
                'location'=>$request->location,
                'type'=>$request->type,
                'image'=>$result,
                'patient_id'=>$patient,
            ]);
            return new VaccineResource($vaccine);
        }
        $vaccine =Vaccine::create([
            'name'=>$request->name,
            'date'=>$request->date,
            'location'=>$request->location,
            'type'=>$request->type,
            'patient_id'=>$patient,
        ]);
        $vaccine=Vaccine::find($vaccine['id']);
        return new VaccineResource($vaccine);

    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $vaccine=Vaccine::where($condition)->get();
        if ($vaccine->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new VaccineResource($vaccine[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $vaccine=Vaccine::where($condition);
        if (!$vaccine)
            return response()->json(['error' => 'vaccine not found'], 404);

        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $vaccine->update([
                'name' => $request->name,
                'date' => $request->date,
                'location' => $request->location,
                'type' => $request->type,
                'image' => $result,
            ]);
            $vaccine=Vaccine::find($id);
            return new VaccineResource($vaccine);
        }

        $vaccine->update([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
        ]);
        $vaccine=Vaccine::find($id);
        return new VaccineResource($vaccine);
    }

    public function destroy($patient,$id)
    {
        $vaccine=Vaccine::find($id);
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);
        $vaccine->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
