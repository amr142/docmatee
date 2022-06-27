<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\VaccineResource;
use App\Models\Vaccine;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;

class VaccineController extends Controller
{
    public function index()
    {
        $vaccine= Auth::guard('patient-api')->user()->vaccine()->get();
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);
        return VaccineResource::collection($vaccine);
    }

    public function create(Request $request)
    {
        $id=Auth::guard('patient-api')->user()->id;
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
                'patient_id'=>$id,
            ]);
            return new VaccineResource($vaccine);
        }

        $vaccine =Vaccine::create([
            'name'=>$request->name,
            'date'=>$request->date,
            'location'=>$request->location,
            'type'=>$request->type,
            'patient_id'=>$id,
        ]);

        $vaccine =Vaccine::find($vaccine['id']);
        return new VaccineResource($vaccine);
    }

    public function show($id)
    {
        $vaccine=Auth::guard('patient-api')->user()->vaccine()->find($id);
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);
        return new VaccineResource($vaccine);
    }

    public function update(Request $request, $id)
    {
        $vaccine=Auth::guard('patient-api')->user()->vaccine()->find($id);
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);

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
            return new VaccineResource($vaccine);
        }

         $vaccine->update([
            'name'=>$request->name,
            'date'=>$request->date,
            'location'=>$request->location,
            'type'=>$request->type,
        ]);
        return new VaccineResource($vaccine);

    }

    public function destroy($id)
    {
        $vaccine=Auth::guard('patient-api')->user()->vaccine()->find($id);
        if (!$vaccine)
            return response()->json(['error' => 'not found'], 404);
        $vaccine->delete();
        return response()->json(['message' => 'deleted successfully'], 200);


    }
}
