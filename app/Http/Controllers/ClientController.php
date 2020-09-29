<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Validator;

class ClientController extends Controller
{
    public function create_client(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        $client = new Client;
        $client->client_name = $request->client_name;
        $client->client_title = $request->client_title;
        $client->client_description = $request->client_description;
        $client->image_id = $request->image_id;
        $client->type = $request->type;
        $client->position = $request->position;
        $client->save();

        $client_data = Client::where('id', $client->id)->with('image')->first();
        return response()->json(['data' => $client_data]);
    }

    public function get_client($position, $type)
    {
        $client = Client::where('position', $position)->where('type', $type)->with('image')->orderBy('id', 'asc')->get();
        return response()->json(['data' => $client]);
    }
    public function update_client(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $client = Client::where('id', $request->id)->first();
        if (!$client) {
            return response()->json(['error' => 'not found this Client']);
        }

        $client->client_name = $request->client_name;
        $client->client_title = $request->client_title;
        $client->client_description = $request->client_description;
        $client->image_id = $request->image_id;
        $client->type = $request->type;
        $client->position = $request->position;
        $client->save();

        $client_data = Client::where('id', $client->id)->with('image')->first();
        return response()->json(['data' => $client_data]);
    }
    public function delete_client($i)
    {
        $client = Client::where('id', $i)->first();
        if (!$client) {
            return response()->json(['error' => 'not found this Client']);
        }
        $client->delete();
        return response()->json(['message' => 'done']);
    }
}
