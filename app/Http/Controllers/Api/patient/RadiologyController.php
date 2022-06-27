<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\RadiologyResource;
use App\Models\Radiology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RadiologyController extends Controller
{
    public function index()
    {
        $radiology= Auth::guard('patient-api')->user()->radiology()->get();
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        return RadiologyResource::collection($radiology);
    }

    public function create(Request $request)
    {
        $id=Auth::guard('patient-api')->user()->id;
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $radiology = Radiology::create([
                'name' => $request->name,
                'date' => $request->date,
                'location' => $request->location,
                'type' => $request->type,
                'image' => $result,
                'patient_id' => $id,
            ]);
            return new RadiologyResource($radiology);
        }
        $radiology = Radiology::create([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
            'patient_id' => $id,
        ]);
        $radiology=Radiology::find($radiology['id']);
        return new RadiologyResource($radiology);
    }

    public function show($id)
    {
        $radiology=Auth::guard('patient-api')->user()->radiology()->find($id);
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        return new RadiologyResource($radiology);
    }

    public function update(Request $request, $id)
    {
        $radiology=Auth::guard('patient-api')->user()->radiology()->find($id);
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $radiology->update([
                'name' => $request->name,
                'date' => $request->date,
                'location' => $request->location,
                'type' => $request->type,
                'image' => $result,
            ]);
            return new RadiologyResource($radiology);
        }
        $radiology->update([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
        ]);
        $radiology=Radiology::find($radiology['id']);
        return new RadiologyResource($radiology);
    }

    public function destroy($id)
    {
        $radiology=Auth::guard('patient-api')->user()->radiology()->find($id);
        if (!$radiology)
            return response()->json(['error' => 'not found'], 404);
        $radiology->delete();
        return response()->json(['message' => 'deleted successfully'], 200);

    }
}
