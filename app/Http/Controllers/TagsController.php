<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Tag;
use App\Category;

class TagsController extends Controller
{
    public function create_tags(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $category = Category::where('id', $request->category_id)->where('parent_id', 0)->first();
        if (!$category) {
            return response()->json(['error' => 'You Should Select category'], 422);
        }
        $tags = new Tag;
        $tags->name = $request->name;
        $tags->category_id = $request->category_id;
        $tags->save();

        $data = Tag::where('id', $tags->id)->with('category')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_tags(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $tags = Tag::where('id', $i)->with('category')->first();
        if (!$tags) {
            return response()->json(['error' => 'tags Not Found'], 422);
        }
        $tags->name = $request->name;
        $tags->category_id = $request->category_id;
        $tags->save();

        return response()->json(['data'=>$tags]);
    }
    public function delete_tags($i)
    {
        $tags = Tag::where('id', $i)->with('category')->first();
        if (!$tags) {
            return response()->json(['error' => 'tags Not Found'], 422);
        }
        $tags->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_tags($i)
    {
        $data = Category::where('id', $i)->with('tags')->first();
        if (!$data) {
            return response()->json(['error' => 'Category Not Found'], 422);
        }
        return response()->json(['data'=>$data]);
    }
}
