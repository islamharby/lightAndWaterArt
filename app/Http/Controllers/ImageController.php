<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Validator;
use ArtisansWeb\ImageOptimize\Optimizer;

class ImageController extends Controller
{
    public function create_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'meta_title' => 'required',
            // 'meta_description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        if ($request->hasFile('image')) {
            $a = new Image();
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore =  $fileName . '_' . time() . '.' . $extension;
            $file = $request->image;
            $file->move('Uploads/', $fileNameToStore);
            $a->image_url = 'Uploads/' . $fileNameToStore;
            $a->meta_title = $request->meta_title;
            $a->meta_description = $request->meta_description;
            $a->save();

            $img = new Optimizer();
            $source =  'Uploads/' . $fileNameToStore;
            $img->optimize($source);
            
            return response()->json(['data' => $a]);
        } else {
            return response()->json(['error' => 'You Should Put Image'], 422);
        }
    }
    public function update_image(Request $request, $i)
    {
        $a = Image::where('id', $i)->first();
        if (!$a) {
            return response()->json(['error' => 'Image Not Found'], 422);
        }
        if ($request->hasFile('image')) {
            if ($a->image_url && file_exists(public_path($a->image_url))) {
                unlink(public_path($a->image_url));
            }
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore =  $fileName . '_' . time() . '.' . $extension;
            $file = $request->image;
            $file->move('Uploads/', $fileNameToStore);
            $a->image_url = 'Uploads/' . $fileNameToStore;
        }
        $a->meta_title = $request->meta_title;
        $a->meta_description = $request->meta_description;
        $a->save();
        return response()->json(['data' => $a]);
    }
    public function get_image_by_id($i)
    {
        $a = Image::where('id', $i)->first();
        if (!$a) {
            return response()->json(['error' => 'Image Not Found'], 422);
        }
        return response()->json(['data' => $a]);
    }
}
