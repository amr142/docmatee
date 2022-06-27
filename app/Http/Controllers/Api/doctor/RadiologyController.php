<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\RadiologyResource;
use App\Models\Mypatient;
use App\Models\Radiology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RadiologyController extends Controller
{

    public function index($patient)
    {
        $radiology=Radiology::where('patient_id','=',$patient)->get();
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        return RadiologyResource::collection($radiology);


    }

    public function create(Request $request ,$patient)
    {
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $radiology = Radiology::create([
                'name' => $request->name,
                'date' => $request->date,
                'image' => $result,
                'location' => $request->location,
                'type' => $request->type,
                'patient_id' => $patient,
            ]);
            return new RadiologyResource($radiology);
        }
        $radiology = Radiology::create([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
            'patient_id' => $patient,
        ]);
        $radiology=Radiology::find($radiology['id']);
        return new RadiologyResource($radiology);
    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $radiology=Radiology::where($condition)->get();
        if ($radiology->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new RadiologyResource($radiology[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $radiology=Radiology::where($condition);
        if (!$radiology)
            return response()->json(['error' => 'radiology not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $radiology=$radiology->update([
                'name'=>$request->name,
                'date'=>$request->date,
                'location'=>$request->location,
                'type'=>$request->type,
                'image'=>$result,
            ]);
            $radiology=Radiology::find($id);
            return new RadiologyResource($radiology);
        }
        $radiology=$radiology->update([
            'name'=>$request->name,
            'date'=>$request->date,
            'location'=>$request->location,
            'type'=>$request->type,
        ]);
        $radiology=Radiology::find($id);
        return new RadiologyResource($radiology);
    }

    public function destroy($patient,$id)
    {
        $radiology=Radiology::find($id);
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        $radiology->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
