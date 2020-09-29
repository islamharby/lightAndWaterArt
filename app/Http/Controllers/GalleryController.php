<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;
use Validator;

class GalleryController extends Controller
{
    public function create_gallery(Request $request)
    {
        $gallery = new Gallery;

        if ($request->midea_type == "image") {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:images,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $gallery->image_id = $request->image_id;
        }
        if ($request->midea_type == "video") {
            $validator = Validator::make($request->all(), [
                'video_link' => 'required|url',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $gallery->video_link = $request->video_link;
        }

        $gallery->type = $request->type;
        $gallery->position = $request->position;
        $gallery->midea_type = $request->midea_type;
        $gallery->save();

        $gallery_data = Gallery::where('id', $gallery->id)->with('images')->first();
        return response()->json(['data' => $gallery_data]);
    }

    public function get_gallery(Request $request)
    {
        $page = request()->get('page', 1);
        $amount = request()->get('amount', 10);

        $offset = ($page - 1) * $amount;

        $gallery = Gallery::where('type', $request->type)->with('images')->limit($amount)->offset($offset)->orderBy('id', 'asc')->get();
        $data = Gallery::where('type', $request->type)->with('images')->get();
        $total = $data->count();

        return [
            'data' => $gallery,
            'pag' => [
                'page' => $page,
                'total' => $total,
                'amount' => $amount,
            ],
        ];
    }
    public function delete_gallery($i)
    {
        $gallery = Gallery::where('id', $i)->first();
        if (!$gallery) {
            return response()->json(['error' => 'not found this gallery']);
        }
        $gallery->delete();
        return response()->json(['message' => 'done']);
    }
}
