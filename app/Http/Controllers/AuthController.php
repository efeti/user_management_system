<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::whereEmail($request->email)->first();

        if (($user && Hash::check($credentials['password'], $user->password)) == false)
            return response()->json(['error' => 'Email/Password is Invalid'], 422);

        $role = $user->role == 'admin' ? ['admin'] : ['user'];

        return response()->json([
            'token' => $user->createToken('login_access', $role)->accessToken,
            'profile' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['messsage' => 'successfully logged out']);
    }
}
