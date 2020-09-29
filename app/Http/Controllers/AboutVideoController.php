<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AboutVideo;
use Validator;

class AboutVideoController extends Controller
{
    public function create_video(Request $request)
    {
        $video = new AboutVideo;
        $video->description = $request->description;
        $video->video_url = $request->video_url;
        $video->title = $request->title;
        $video->position = $request->position;
        $video->save();

        $data = AboutVideo::where('id', $video->id)->first();
        return response()->json(['data'=>$data]);
    }
    public function update_video(Request $request, $i)
    {
        $video = AboutVideo::where('id', $i)->first();
        if (!$video) {
            return response()->json(['error' => 'Video Not Found'], 422);
        }
        $video->description = $request->description;
        $video->video_url = $request->video_url;
        $video->title = $request->title;
        $video->position = $request->position;
        $video->save();

        return response()->json(['data'=>$video]);
    }
    public function delete_video($i)
    {
        $Video = AboutVideo::where('id', $i)->first();
        if (!$Video) {
            return response()->json(['error' => 'Video Not Found'], 422);
        }
        $Video->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_video($i)
    {
        $data = AboutVideo::where('position', $i)->orderBy('id', 'asc')->get();
        if (count($data)>0) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'Videos Not Found'], 404);
        }
    }
}
