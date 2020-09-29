<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutCarousel;
use Validator;

class AboutCarouselController extends Controller
{
    public function create_carousel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $carousel = new AboutCarousel;
        $carousel->name = $request->name;
        $carousel->image_id = $request->image_id;
        $carousel->type = $request->type;
        $carousel->save();

        $data = AboutCarousel::where('id', $carousel->id)->with('image')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_carousel(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $carousel = AboutCarousel::where('id',$i)->with('image')->first();
        if (!$carousel) {
            return response()->json(['error' => 'carousel Not Found'], 422);
        }
        $carousel->name = $request->name;
        $carousel->image_id = $request->image_id;
        $carousel->type = $request->type;
        $carousel->save();

        return response()->json(['data'=>$carousel]);
    }
    public function delete_carousel($i)
    {
        $carousel = AboutCarousel::where('id', $i)->with('image')->first();
        if (!$carousel) {
            return response()->json(['error' => 'carousel Not Found'], 422);
        }
        $carousel->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_carousel()
    {
        $data = AboutCarousel::with('image')->orderBy('id', 'asc')->get();
        if (count($data)>0) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'Not Found carousel'], 404);
        }
    }
}
