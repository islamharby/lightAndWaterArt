<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\ProductImage;
use App\Product;

class ProductImageController extends Controller
{
    public function create_images_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $image = new ProductImage;
        $image->image_id = $request->image_id;
        $image->product_id = $request->product_id;
        $image->save();

        $data = ProductImage::where('id', $image->id)->with('image')->with('product')->first();
        return response()->json(['data'=>$data]);
    }
    public function update_images_product(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
            'product_id' => 'exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $image = ProductImage::where('id', $i)->with('image')->with('product')->first();
        if (!$image) {
            return response()->json(['error' => 'image Not Found'], 422);
        }
        $image->image_id = $request->image_id;
        $image->product_id = $request->product_id;
        $image->save();

        return response()->json(['data'=>$image]);
    }
    public function delete_images_product(Request $request)
    {
        $image = ProductImage::where('image_id', $request->image_id)->where('product_id', $request->product_id)->with('image')->with('product')->first();
        if (!$image) {
            return response()->json(['error' => 'image Not Found'], 422);
        }
        $image_count  = ProductImage::where('product_id', $request->product_id)->count();
        if ($image_count == 1) {
            return response()->json(['error'=>'You Can Not Delete THis Image'],422);
        }
        $image->delete();
        return response()->json(['message'=>'done']);
    }
    public function get_product_images($i)
    {
        $data = Product::where('id', $i)->with('images')->first();
        if ($data) {
            return response()->json(['data'=>$data]);
        } else {
            return response()->json(['error'=>'Product  not found'], 404);
        }
    }
}
