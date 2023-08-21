<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $cred = $request->validated();

        if (!Auth::attempt(['email' => $cred['email'], 'password' => $cred['password']], $cred['remember'])) {
            $data['error'] = true;
            $data['msg'] = 'The provided credentials do not match our records.';
        } else {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken; //new token

            $data['token'] = $token;
            $data['user'] = $user;
            $data['error'] = false;
        }

        return response()->json($data);
    }

    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        
        $user = User::create($fields);

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        $data['user'] = $user;
        $data['token'] = $token;

        return response()->json($data, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $data['error'] = false;
        $data['msg'] = 'Logged Out';

        return response()->json($data);
    }

    public function logoutAllDeviice(Request $request)
    {
        $request->user()->tokens()->delete();

        $data['error'] = false;
        $data['msg'] = 'Logged out on all devices';

        return response()->json($data);
    }
}
