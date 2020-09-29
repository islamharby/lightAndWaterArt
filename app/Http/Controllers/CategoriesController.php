<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{
    public function create_Category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:images,id',
            'name' => 'required',
            'type' => 'required',
            'parent_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $category_name = Category::where('parent_id', $request->parent_id)->where('name', $request->name)->where('type', $request->type)->first();
        if ($category_name) {
            return response()->json(['error' => 'this name has taken'], 422);
        }
        $category = new Category;
        $category->parent_id = $request->parent_id;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->image_id = $request->image_id;
        $category->type = $request->type;
        $category->save();

        $data = Category::where('id', $category->id)->with('image')->first();
        return response()->json(['data' => $data]);
    }
    public function update_Category(Request $request, $i)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'exists:images,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $category = Category::where('id', $i)->with('image')->first();
        if (!$category) {
            return response()->json(['error' => 'category Not Found'], 422);
        }
        $category->parent_id = $request->parent_id;
        if ($request->name) {
            if ($request->name != $category->name) {
                $check_name = Category::where('parent_id', $request->parent_id)->where('name', $request->name)->whereNotIn('id', [$i])->where('type', $request->type)->first();

                if ($check_name) {
                    return response()->json(['error' => 'this name has taken '], 422);
                }
                $category->name = $request->name;
            }
            $category->name = $request->name;
        }
        $category->description = $request->description;
        $category->image_id = $request->image_id;
        $category->type = $request->type;
        $category->save();

        return response()->json(['data' => $category]);
    }
    public function delete_Category($i)
    {
        $sub_ids = [];
        $category = Category::where('id', $i)->with('image')->first();
        if (!$category) {
            return response()->json(['error' => 'category Not Found'], 422);
        }
        $sub_cat = Category::where('parent_id', $i)->get();
        if (\count($sub_cat) > 0) {
            foreach ($sub_cat as $key => $sub) {
                $sub_ids[] = $sub->id;
            }
        }
        if (count($sub_ids) > 0) {
            $sub_sub_cat = Category::whereIn('parent_id', $sub_ids)->get();
            if (\count($sub_sub_cat) > 0) {
                foreach ($sub_sub_cat as $key => $sub_sub) {
                    $sub_ids[] = $sub_sub->id;
                }
            }
            $products = Product::whereIn('category_id', $sub_ids)->get();
            if (\count($products) > 0) {
                foreach ($products as $key => $product) {
                    $product->delete();
                }
            }
            $cat = Category::whereIn('parent_id', $sub_ids)->get();
            if (\count($cat) > 0) {
                foreach ($cat as $key => $value) {
                    $value->delete();
                }
            }
            $sub = Category::whereIn('id', $sub_ids)->get();
            if (\count($cat) > 0) {
                foreach ($cat as $key => $value) {
                    $value->delete();
                }
            }
        }
        if ($category->image && file_exists('Upload/' . $category->image)) {
            unlink('Upload/' . $category->image);
        }
        $category->delete();
        return response()->json(['message' => 'done']);
    }
    public function get_parent_cat($i)
    {
        $data = Category::where('parent_id', 0)->where('type', $i)->with('image')->orderBy('id', 'asc')->get();
        if (count($data) > 0) {
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Not Found category'], 404);
        }
    }
    public function get_sub_cat($i)
    {
        $category = Category::where('id', $i)->with('image')->orderBy('id', 'asc')->first();
        if (!$category) {
            return response()->json(['error' => 'Not Found category'], 404);
        }
        $sub_categories = Category::where('parent_id', $i)->with('image')->orderBy('id', 'asc')->orderBy('id', 'asc')->get();
        if (count($sub_categories) > 0) {
            return response()->json(['category' => $category, 'sub_categories' => $sub_categories]);
        } else {
            return response()->json(['error' => 'Not Found Sub category'], 404);
        }
    }
    public function get_cat_by_id($i)
    {
        $category = Category::where('id', $i)->with('image')->first();
        if (!$category) {
            return response()->json(['error' => 'Not Found category'], 404);
        }
        $sub_categories = Category::where('parent_id', $i)->with('image')->orderBy('id', 'asc')->get();
        if (count($sub_categories) > 0) {
            return response()->json(['category' => $category, 'sub_categories' => $sub_categories]);
        } else {
            return response()->json(['error' => 'Not Found Sub category'], 404);
        }
    }
}
