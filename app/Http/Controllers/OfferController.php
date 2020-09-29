<?php

namespace App\Http\Controllers;

use App\Offer;
use Illuminate\Http\Request;
use Validator;

class OfferController extends Controller
{
    public function create_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        $offer = new Offer;
        $offer->title = $request->title;
        $offer->discount = $request->discount;
        $offer->description = $request->description;
        $offer->image_id = $request->image_id;
        $offer->type = $request->type;
        $offer->position = $request->position;
        $offer->save();

        $offer_data = Offer::where('id', $offer->id)->with('image')->first();
        return response()->json(['data' => $offer_data]);
    }

    public function get_offer($position, $type)
    {
        $offer = Offer::where('position', $position)->where('type', $type)->with('image')->orderBy('id', 'asc')->get();
        return response()->json(['data' => $offer]);
    }

    public function update_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $offer = Offer::where('id', $request->id)->first();
        if (!$offer) {
            return response()->json(['error' => 'not found this Offer']);
        }

        $offer->title = $request->title;
        $offer->discount = $request->discount;
        $offer->description = $request->description;
        $offer->image_id = $request->image_id;
        $offer->type = $request->type;
        $offer->position = $request->position;
        $offer->save();

        $offer_data = Offer::where('id', $offer->id)->with('image')->first();
        return response()->json(['data' => $offer_data]);
    }
    public function delete_offer($i)
    {
        $offer = Offer::where('id', $i)->first();
        if (!$offer) {
            return response()->json(['error' => 'not found this Offer']);
        }
        $offer->delete();
        return response()->json(['message' => 'done']);
    }
}
