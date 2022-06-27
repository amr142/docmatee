<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrescriptionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Collection;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescription= Auth::guard('patient-api')->user()->prescription()->get();
        if (!$prescription)
            return response()->json(['error' => 'not found'], 404);
        return PrescriptionResource::collection($prescription);
    }
    public function show($id)
    {
        $prescription=Auth::guard('patient-api')->user()->prescription()->find($id);
        if (!$prescription)
            return response()->json(['error' => 'not found'], 404);
        return new PrescriptionResource($prescription);
    }
}
