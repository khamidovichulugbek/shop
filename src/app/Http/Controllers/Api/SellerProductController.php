<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerProductController extends Controller
{
    public function addProducts(Request $request)
    {
        try {

            $validateProduct = Validator::make($request->all(), [
                'name' => ['required'],
                'shopId' => ['required']
            ]);

            if ($validateProduct->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error Validate',
                    'errors' => $validateProduct->errors()
                ], 401);
            }

            $shop = Shop::where('user_id', $request->user()->id);

            $shop->findOrFail($request->shopId);

            Product::create([
                'name' => $request->name,
                'user_id' => $request->user()->id,
                'shop_id' => $request->shopId,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Product Created',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getProducts()
    {
        $products = Product::all();

        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }

    public function updateProducts(Request $request, $id)
    {
        try {
            $validateProduct = Validator::make($request->all(), [
                'name' => ['required'],
            ]);

            if ($validateProduct->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error Validate',
                    'errors' => $validateProduct->errors()
                ], 401);
            }


            $products = Product::where('user_id', $request->user()->id);
            $products->findOrFail($id);
            $products->update([
                'name' => $request->name
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Shop updated',

            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteProducts(Request $request, $id)
    {
        $products = Product::where('user_id', $request->user()->id)->where('id', $id)->firstOrFail();
        $products->delete();

        return response()->json([
            'status' => true,
            'message' => "Delete $id"
        ], 200);
    }
}
