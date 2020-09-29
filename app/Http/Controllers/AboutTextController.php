<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\AboutText;

class AboutTextController extends Controller
{
    public function create_text(Request $request)
    {
        $text = new AboutText;
        $text->description = $request->description;
        $text->target_link = $request->target_link;
        $text->title = $request->title;
        $text->position = $request->position;
        $text->save();

        $data = AboutText::where('id', $text->id)->first();

        return response()->json(['data'=>$data]);
    }
    public function update_text(Request $request, $i)
    {
        $text = AboutText::where('id', $i)->first();
        if (!$text) {
            return response()->json(['error' => 'Text Not Found'], 422);
        }
        $text->description = $request->description;
        $text->target_link = $request->target_link;
        $text->title = $request->title;
        $text->position = $request->position;
        $text->save();

        return response()->json(['data'=>$text]);
    }
    public function delete_text($i)
    {
        $text = AboutText::where('id', $i)->first();
        if (!$text) {
            return response()->json(['error' => 'text Not Found'], 422);
        }
        $text->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_text($i)
    {
        $data = AboutText::where('position',$i)->orderBy('id', 'asc')->get();
        if (count($data)>0) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'text Not Found'], 404);
        }
    }
}
