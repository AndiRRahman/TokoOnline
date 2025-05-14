<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Adjusted min length to 8 for better security
        ], [
            'email.unique' => 'Email telah tersedia.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['success' => "Pengguna '{$user->name}' berhasil ditambahkan.", 'data' => $user], 201);
    }

    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
        ], [
            'email.unique' => 'Email telah tersedia.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user->update($request->only(['name', 'email']));
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }
        $user->delete();
        return response()->json(null, 204);
    }
}