<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (
            $user &&
            Hash::check($password, $user->password)
        ) {
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid email or password'], 401);
    }

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function userByToken(Request $request)
    {
        return response()->json(['user' => Auth::user()], 200);
    }
}
