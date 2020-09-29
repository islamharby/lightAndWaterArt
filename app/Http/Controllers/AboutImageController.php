<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutImages;
use Validator;

class AboutImageController extends Controller
{
    public function create_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $image = new AboutImages;
        $image->name = $request->name;
        $image->image_id = $request->image_id;
        $image->position = $request->position;
        $image->save();

        $data = AboutImages::where('id', $image->id)->with('image')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_image(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $image = AboutImages::where('id', $i)->with('image')->first();
        if (!$image) {
            return response()->json(['error' => 'image Not Found'], 422);
        }
        $image->name = $request->name;
        $image->image_id = $request->image_id;
        $image->position = $request->position;
        $image->save();

        return response()->json(['data'=>$image]);
    }
    public function delete_image($i)
    {
        $image = AboutImages::where('id', $i)->with('image')->first();
        if (!$image) {
            return response()->json(['error' => 'image Not Found'], 422);
        }
        $image->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_image($i)
    {
        $data = AboutImages::where('position', $i)->with('image')->orderBy('id', 'asc')->get();
        if (count($data)>0) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'image about not found'], 404);
        }
    }
}
