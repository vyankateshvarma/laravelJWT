<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\UserResource;
class AuthController extends Controller
{
    // 🔹 Login and get token
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        return response()->json([
        'data' => new UserResource($user),
        'token' => $token
    ]);    
}

    // 🔹 Register user
    public function register(UserRegisterRequest $request)
    {
        $validatedData=$request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token=auth('api')->login ($user);
        return response()->json([
        'data' => new UserResource($user),
        'token' => $token
    ]);
    }

    // 🔹 Get user data (requires token)
    public function me()
    {
        return response()->json(Auth::user());
    }

    // 🔹 Logout user
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // 🔹 Refresh token
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    // 🔹 Helper
    protected function respondWithToken($token)
    {
        return response()->json([
            'success'=> true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth('api')->factory()->getTTL() * 60
        ]);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User soft deleted successfully']);
    }   
} 
