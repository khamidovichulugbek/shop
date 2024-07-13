<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function addShop(Request $request)
    {
        try {
            $shopValidate = Validator::make($request->all(), [
                'name' => ['required']
            ]);

            if ($shopValidate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error validate',
                    'errors' => $shopValidate->errors()
                ]);
            }

            Shop::create([
                'name' => $request->name,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Shop created',

            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getShop(Request $request)
    {
        $userId = $request->user()->id;

        $shops = Shop::all();

        return response()->json([
            'status' => true,
            'shops' => $shops,
        ], 200);
    }

    public function updateShop(Request $request, $id)
    {
        try {
            $shopValidate = Validator::make($request->all(), [
                'name' => ['required']
            ]);

            if ($shopValidate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error validate',
                    'errors' => $shopValidate->errors()
                ]);
            }

            $shop = Shop::findOrFail($id);;

            $shop->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Shop updated',

            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteShop($id)
    {
        Shop::destroy($id);

        return response()->json([
            'status' => true,
            'message' => "Delete $id"
        ], 200);
    }
}
