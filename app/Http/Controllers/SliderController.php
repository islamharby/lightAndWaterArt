<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;
use Validator;

class SliderController extends Controller
{
    public function create_slider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $slider = new Slider;
        $slider->title = $request->title;
        $slider->image_id = $request->image_id;
        $slider->description = $request->description;
        $slider->target_Link = $request->target_Link;
        $slider->type = $request->type;
        $slider->save();

        $data = Slider::where('id', $slider->id)->where('type', $request->type)->with('image')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_slider(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'The selected image id is invalid'], 422);
        }
        $slider = Slider::where('id', $i)->with('image')->first();
        if (!$slider) {
            return response()->json(['error' => 'Slider Not Found'], 422);
        }
        $slider->title = $request->title;
        $slider->image_id = $request->image_id;
        $slider->description = $request->description;
        $slider->target_Link = $request->target_Link;
        $slider->type = $request->type;
        $slider->save();

        return response()->json(['data'=>$slider]);
    }
    public function get_slider($i)
    {
        $data = Slider::where('type', $i)->with('image')->orderBy('id', 'asc')->get();
        if (count($data)>0) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'Not Found Silders'], 404);
        }
    }
    public function delete_slider($i)
    {
        $slider = Slider::where('id', $i)->with('image')->first();
        if (!$slider) {
            return response()->json(['error' => 'Slider Not Found'], 422);
        }
        $slider->delete();
        return response()->json(['message'=>'done']);
    }
}
