<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventImage;
use Illuminate\Http\Request;
use Validator;

class EventController extends Controller
{
    public function create_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required',
            'name' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        $event = new Event;
        $event->name = $request->name;
        $event->description = $request->description;
        if ($request->position == 'top') {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:images,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $event->target_link = $request->target_link;
            $event->image_id = $request->image_id;
        } else {
            $validator = Validator::make($request->all(), [
                'video_link' => 'required|url',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 422);
            }
            $event->video_link = $request->video_link;
        }

        $event->type = $request->type;
        $event->position = $request->position;
        $event->save();

        if ($request->position == 'bottom') {
            if ($request->ids_images) {
                foreach ($request->ids_images as $key => $value) {
                    $image_event = new EventImage;
                    $image_event->event_id = $event->id;
                    $image_event->image_id = $value;
                    $image_event->save();
                }
            }
        }
        $event_data = Event::where('id', $event->id)->with('image')->with('images')->first();
        return response()->json(['data' => $event_data]);
    }

    public function get_events($position)
    {
        $event = Event::orderBy('id', 'asc')->where('position', $position)->with('image')->with('images')->get();
        return response()->json(['data' => $event]);
    }
    public function update_event(Request $request)
    {
        $event = Event::where('id', $request->id)->first();
        if (!$event) {
            return response()->json(['error' => 'not found this Event']);
        }
        $event->name = $request->name;
        $event->description = $request->description;
        if ($request->position == 'top') {
            $event->target_link = $request->target_link;
            $event->image_id = $request->image_id;
        } else {
            $event->video_link = $request->video_link;
        }

        $event->type = $request->type;
        $event->position = $request->position;
        $event->save();

        if ($request->position == 'bottom') {
            if ($request->ids_images) {
                $image_event = EventImage::where('event_id', $event->id)->get();
                if (count($image_event)>0) {
                    foreach ($image_event as $key => $item) {
                        $item->delete();
                    }
                }
                foreach ($request->ids_images as $key => $value) {
                    $image_event = new EventImage;
                    $image_event->event_id = $event->id;
                    $image_event->image_id = $value;
                    $image_event->save();
                }
            }
        }
        $event_data = Event::where('id', $event->id)->with('image')->with('images')->first();
        return response()->json(['data' => $event_data]);
    }
    public function delete_event($i)
    {
        $event = Event::where('id', $i)->first();
        if (!$event) {
            return response()->json(['error' => 'not found this Event']);
        }
        $event->delete();
        return response()->json(['message' => 'done']);
    }
    public function get_event_by_id($position, $i)
    {
        $event = Event::where('id', $i)->where('position', $position)->with('image')->with('images')->first();
        if (!$event) {
            return response()->json(['error' => 'not found this Event']);
        }
        return response()->json(['data' => $event]);
    }
}
