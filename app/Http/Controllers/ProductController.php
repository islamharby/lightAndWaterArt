<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function create_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $Product = new Product;
        if ($request->name) {
            $ids = [];
            $category = Category::where('id', $request->category_id)->first();
            $categories = Category::where('type', $category->type)->get();
            foreach ($categories as $key => $value) {
                $ids[] = $value->id;
            }
            $names = Product::whereIn('category_id', $ids)->get();
            if (\count($names) > 0) {
                foreach ($names as $key => $value) {
                    $strtoupper = strtoupper($request->name);
                    $strtolower = strtolower($request->name);
                    if ($value->name == $strtoupper) {
                        return response()->json(['error' => 'This name has taken'], 422);
                    } elseif ($value->name == $strtolower) {
                        return response()->json(['error' => 'This name has taken'], 422);
                    } else {
                        $Product->name = $request->name;
                    }
                }
            } else {
                $Product->name = $request->name;
            }
        }
        $Product->description = $request->description;
        $Product->category_id = $request->category_id;
        $Product->dimensions = $request->dimensions;
        $Product->save();

        $data = Product::where('id', $Product->id)->with('category')->first();
        return response()->json(['data' => $data]);
    }
    public function update_product(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $Product = Product::where('id', $i)->with('category')->first();
        if (!$Product) {
            return response()->json(['error' => 'Product Not Found'], 422);
        }
        if ($request->name) {
            if ($request->name != $Product->name) {
                $ids = [];
                $category = Category::where('id', $request->category_id)->first();
                $categories = Category::where('type', $category->type)->get();
                foreach ($categories as $key => $value) {
                    $ids[] = $value->id;
                }
                $names = Product::whereNotIn('id', [$i])->whereIn('category_id', $ids)->get();
                if (\count($names) > 0) {
                    foreach ($names as $key => $value) {
                        $strtoupper = strtoupper($request->name);
                        $strtolower = strtolower($request->name);
                        if ($value->name == $strtoupper) {
                            return response()->json(['error' => 'This name has taken'], 422);
                        } elseif ($value->name == $strtolower) {
                            return response()->json(['error' => 'This name has taken'], 422);
                        } else {
                            $Product->name = $request->name;
                        }
                    }
                }
            }
            $Product->name = $request->name;
        }
        $Product->description = $request->description;
        $Product->category_id = $request->category_id;
        $Product->dimensions = $request->dimensions;
        $Product->save();

        return response()->json(['data' => $Product]);
    }
    public function delete_product($i)
    {
        $Product = Product::where('id', $i)->with('category')->first();
        if (!$Product) {
            return response()->json(['error' => 'category Not Found'], 422);
        }
        $Product->delete();
        return response()->json(['message' => 'done']);
    }
    public function get_products_by_cat(Request $request)
    {
        $cat = $request->ids_cat;
        $array_ids = [];
        $category = Category::whereIn('id', $cat)->get();
        foreach ($category as $key => $value) {
            $array_ids[] = $value->id;
        }
        $sub_category = Category::whereIn('parent_id', $cat)->get();
        foreach ($sub_category as $key => $item) {
            $array_ids[] = $item->id;
        }
        $sub_sub_category = Category::whereIn('parent_id', $array_ids)->get();
        foreach ($sub_sub_category as $key => $sub_cat) {
            $array_ids[] = $sub_cat->id;
        }
        $page = request()->get('page', 1);
        $amount = request()->get('amount', 10);

        $offset = ($page - 1) * $amount;

        $data = Product::orderBy('id', 'asc')->whereIn('category_id', $array_ids)
            ->with(
                array('images' => function ($query) {
                    $query->select('image_id as id', 'meta_title', 'meta_description', 'image_url')->orderBy('image_id', 'asc');
                })
            )->limit($amount)->offset($offset)->get();
        $data_total = Product::whereIn('category_id', $array_ids)->get();
        $total = $data_total->count();

        return [
            'data' => $data,
            'pag' => [
                'page' => $page,
                'total' => $total,
                'amount' => $amount,
            ],
        ];
        if ($data) {
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Not Found category'], 404);
        }
    }
    public function get_product_by_id($i)
    {
        $Product = Product::where('id', $i)->with('images')->with('category')->first();
        if (!$Product) {
            return response()->json(['error' => 'Not Found Product'], 404);
        }
        return response()->json(['data' => $Product]);
    }
    public function get_product(Request $request)
    {
        $Product = Product::with('images')->orderBy('id', 'asc')->get();
        return response()->json(['data' => $Product]);
    }
    public function get_product_by_type($i)
    {
        $ids = [];
        $category_check = Category::where('type', $i)->orderBy('id', 'asc')->get();
        if (count($category_check) > 0) {
            foreach ($category_check as $key => $value) {
                $ids[] = $value->id;
            }
        }
        $Product = Product::whereIn('category_id', $ids)->with('images')->orderBy('id', 'asc')->get();
        return response()->json(['data' => $Product]);
    }
}
