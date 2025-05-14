<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        if (Product::where('name', $request->name)->exists()) {
            return response()->json(['error' => "Nama produk '{$request->name}' telah tersedia."], 400);
        }

        $product = Product::create($request->only(['name', 'price', 'description', 'stock']));
        // return response()->json(['success' => "Produk '{$product->name}' berhasil ditambahkan.", 'data' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        if ($request->has('name') && $request->name !== $product->name && Product::where('name', $request->name)->exists()) {
            return response()->json(['error' => "Nama produk '{$request->name}' telah tersedia."], 400);
        }

        $product->update($request->only(['name', 'price', 'description', 'stock']));
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }
        $product->delete();
        return response()->json(null, 204);
    }
}