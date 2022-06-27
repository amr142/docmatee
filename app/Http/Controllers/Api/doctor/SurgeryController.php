<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurgeryResource;
use App\Models\Mypatient;
use App\Models\Surgery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SurgeryController extends Controller
{

    public function index($patient)
    {
        $surgery=Surgery::where('patient_id','=',$patient)->get();
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        return SurgeryResource::collection($surgery);


    }

    public function create(Request $request ,$patient)
    {

        if($request->hasFile('image')){
            $file = time() .'.'.request()->file('image')->extension();
            request ()->file('image')->storeAs('public',$file);
            $result="http://docmate.herokuapp.com/storage/".$file;
            $surgery =Surgery::create([
                'name'=>$request->name,
                'location'=>$request->location,
                'type'=>$request->type,
                'date'=>$request->date,
                'image'=>$result,
                'patient_id'=>$patient,
            ]);
            return new SurgeryResource($surgery);
        }
        $surgery =Surgery::create([
            'name'=>$request->name,
            'location'=>$request->location,
            'type'=>$request->type,
            'date'=>$request->date,
            'patient_id'=>$patient,
        ]);
        $surgery=Surgery::find($surgery['id']);
        return new SurgeryResource($surgery);

    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $surgery=Surgery::where($condition)->get();
        if ($surgery->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new SurgeryResource($surgery[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $surgery=Surgery::where($condition);
        if (!$surgery)
            return response()->json(['error' => 'surgery not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $surgery=$surgery->update([
                'name'=>$request->name,
                'location'=>$request->location,
                'type'=>$request->type,
                'date'=>$request->date,
                'image'=>$result,
            ]);
            $surgery=Surgery::find($id);
            return new SurgeryResource($surgery);
        }
        $surgery=$surgery->update([
            'name'=>$request->name,
            'location'=>$request->location,
            'type'=>$request->type,
            'date'=>$request->date,
        ]);
        $surgery=Surgery::find($id);
        return new SurgeryResource($surgery);
    }

    public function destroy($patient,$id)
    {
        $surgery=Surgery::find($id);
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        $surgery->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
