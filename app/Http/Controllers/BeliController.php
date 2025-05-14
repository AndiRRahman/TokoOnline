<?php

namespace App\Http\Controllers;

use App\Models\Beli;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class BeliController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }

        if ($request->quantity > $product->stock) {
            return response()->json(['error' => 'Stok kurang.'], 400);
        }

        $purchase = Beli::create($request->only(['user_id', 'product_id', 'quantity']));
        $product->stock -= $request->quantity;
        $product->save();

        return response()->json([
            // 'success' => "Pembelian berhasil: {$purchase->quantity} unit {$product->name} oleh pengguna {$user->name}.",
            'data' => $purchase
        ], 201);
    }

    public function index()
    {
        return response()->json(Beli::all(), 200);
    }
}