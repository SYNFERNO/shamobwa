<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id)
        {
            $product = Product::with(['category','galleries'])->find($id);

            if($product)
            {
                return ResponseFormatter::success($product, 'Success');
            }
            else
            {
                return ResponseFormatter::error(null, 'Product not found', 404);
            }
        }

        $products = Product::with(['category','galleries']);

        if($name)
        {
            $products = $products->where('name', 'like', '%'.$name.'%');
        }

        if($description)
        {
            $products = $products->where('description', 'like', '%'.$description.'%');
        }

        if($tags)
        {
            $products = $products->where('tags', 'like', '%'.$tags.'%');
        }

        if($price_from)
        {
            $products = $products->where('price', '>=', $price_from);
        }

        if($price_to)
        {
            $products = $products->where('price', '<=', $price_to);
        }

        if($categories)
        {
            $products = $products->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $products->paginate($limit), 
            'Success'
        );
    }
}
