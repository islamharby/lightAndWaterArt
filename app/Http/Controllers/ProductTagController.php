<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\ProductsTag;
use App\Tag;

class ProductTagController extends Controller
{
    public function create_tag_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_id' => 'required|exists:tags,id',
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $tag = new ProductsTag;
        $tag->tag_id = $request->tag_id;
        $tag->product_id = $request->product_id;
        $tag->save();

        $data = ProductsTag::where('id', $tag->id)->with('tag')->with('product')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_tag_product(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'tag_id' => 'exists:tags,id',
            'product_id' => 'exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $tag = ProductsTag::where('id', $i)->with('tag')->with('product')->first();
        if (!$tag) {
            return response()->json(['error' => 'tag Not Found'], 422);
        }
        $tag->tag_id = $request->tag_id;
        $tag->product_id = $request->product_id;
        $tag->save();

        return response()->json(['data'=>$tag]);
    }
    public function delete_tag_product($i)
    {
        $tag = ProductsTag::where('id', $i)->with('tag')->with('product')->first();
        if (!$tag) {
            return response()->json(['error' => 'tag Not Found'], 422);
        }
        $tag->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_products_by_tag($i)
    {
        $data = Tag::where('id', $i)->with('products')->first();
        if ($data) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'Tag not found'], 404);
        }
    }
}
