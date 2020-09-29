<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Validator;

class ServiceController extends Controller
{
    public function create_service(Request $request)
    {
        $service = new Service;
        if ($request->name) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:services,name',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $service->name = $request->name;
        }
        if ($request->image_id) {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:images,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $service->image_id = $request->image_id;
        }
        $service->save();
        return response()->json(['data' => $service]);
    }
    public function get_service()
    {
        $service = Service::with('image')->orderBy('id', 'asc')->get();
        return response()->json(['data' => $service]);
    }
    public function update_service(Request $request)
    {
        $service = Service::where('id', $request->id)->first();
        if (!$service) {
            return response()->json(['error' => 'not found this service']);
        }
        if ($request->name) {
            $service->name = $request->name;
        }
        if ($request->image_id) {
            $validator = Validator::make($request->all(), [
                'image_id' => 'exists:images,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $service->image_id = $request->image_id;
        }
        $service->save();
        return response()->json(['message' => 'done']);
    }
    public function delete_service($i)
    {
        $service = Service::where('id', $i)->first();
        if (!$service) {
            return response()->json(['error' => 'not found this service']);
        }
        $service->delete();
        return response()->json(['message' => 'done']);
    }
}
