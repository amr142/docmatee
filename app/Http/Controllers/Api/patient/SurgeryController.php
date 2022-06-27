<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurgeryResource;
use App\Models\Surgery;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurgeryController extends Controller
{
    public function index()
    {
        $surgery= Auth::guard('patient-api')->user()->surgery()->get();
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        return surgeryResource::collection($surgery);
    }

    public function create(Request $request)
    {
        $id=Auth::guard('patient-api')->user()->id;
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $surgery = surgery::create([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'date' => $request->date,
                'image' => $result,
                'patient_id' => $id,
            ]);
            return new surgeryResource($surgery);
        }
        $surgery = surgery::create([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'patient_id' => $id,
        ]);
        $surgery=Surgery::find($surgery['id']);
        return new surgeryResource($surgery);
    }

    public function show($id)
    {
        $surgery=Auth::guard('patient-api')->user()->surgery()->find($id);
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        return new surgeryResource($surgery);
    }

    public function update(Request $request, $id)
    {
        $surgery=Auth::guard('patient-api')->user()->surgery()->find($id);
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $surgery->update([
                'name' => $request->name,
                'location' => $request->location,
                'type' => $request->type,
                'date' => $request->date,
                'image' => "https://i.ibb.co/n7yGWqz/mm.png",
                'patient_id' => $id,
            ]);
            return new surgeryResource($surgery);
        }
        $surgery->update([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'date' => $request->date,
            'image' => "https://i.ibb.co/n7yGWqz/mm.png",
            'patient_id' => $id,
        ]);
        $surgery=Surgery::find($surgery['id']);
        return new surgeryResource($surgery);
    }

    public function destroy($id)
    {
        $surgery=Auth::guard('patient-api')->user()->surgery()->find($id);
        if (!$surgery)
            return response()->json(['error' => 'not found'], 404);
        $surgery->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
