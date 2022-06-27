<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabResource;
use App\Models\Mypatient;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LabController extends Controller
{

    public function index($patient)
    {
        $lab=Lab::where('patient_id','=',$patient)->get();
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        return LabResource::collection($lab);


    }

    public function create(Request $request ,$patient)
    {
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $lab = Lab::create([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'image' => $result,
                'date' => $request->date,
                'patient_id' => $patient,
            ]);
            return new LabResource($lab);
        }
        $lab = Lab::create([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'patient_id' => $patient,
        ]);
        $lab=Lab::find($lab['id']);
        return new LabResource($lab);
    }

    public function show($patient,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $lab=Lab::where($condition)->get();
        if ($lab->isEmpty())
            return response()->json(['error' => 'not found'], 404);
        return new LabResource($lab[0]);
    }

    public function update($patient,Request $request,$id)
    {
        $condition=['patient_id'=>$patient,'id'=>$id];
        $lab=Lab::where($condition);
        if (!$lab)
            return response()->json(['error' => 'lab not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $lab = $lab->update([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'date' => $request->date,
                'image' => $result,
            ]);
            $lab = Lab::find($id);
            return new LabResource($lab);
        }
        $lab = $lab->update([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'image_result' => "https://i.ibb.co/n7yGWqz/mm.png",
        ]);
        $lab = Lab::find($lab['id']);
        return new LabResource($lab);
    }

    public function destroy($patient,$id)
    {
        $lab=Lab::find($id);
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        $lab->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
