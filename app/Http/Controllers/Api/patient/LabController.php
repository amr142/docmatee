<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabResource;
use App\Models\Lab;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    public function index()
    {
        $lab= Auth::guard('patient-api')->user()->lab()->get();
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        return LabResource::collection($lab);
    }

    public function create(Request $request)
    {
        $id=Auth::guard('patient-api')->user()->id;
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $lab = Lab::create([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'date' => $request->date,
                'image' => $result,
                'patient_id' => $id,
            ]);
            return new LabResource($lab);
        }
        $lab = Lab::create([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'patient_id' => $id,
        ]);
        $lab=Lab::find($lab['id']);
        return new LabResource($lab);

    }

    public function show($id)
    {
        $lab=Auth::guard('patient-api')->user()->lab()->find($id);
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        return new LabResource($lab);
    }

    public function update(Request $request, $id)
    {
        $lab=Auth::guard('patient-api')->user()->lab()->find($id);
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $lab->update([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'date' => $request->date,
                'image_result' => "https://i.ibb.co/n7yGWqz/mm.png",
            ]);
            return new LabResource($lab);
        }
        $lab->update([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'image_result' => "https://i.ibb.co/n7yGWqz/mm.png",
        ]);
        $lab=Lab::find($lab['id']);
        return new LabResource($lab);
    }

    public function destroy($id)
    {
        $lab=Auth::guard('patient-api')->user()->lab()->find($id);
        if (!$lab)
            return response()->json(['error' => 'not found'], 404);
        $lab->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
