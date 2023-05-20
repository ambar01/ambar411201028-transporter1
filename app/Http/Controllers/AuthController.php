<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Kurir;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:kurirs',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $kurir = new Kurir();
        $kurir->name = $request->input('name');
        $kurir->email = $request->input('email');
        $kurir->password = bcrypt($request->input('password'));
        $kurir->save();

        $token = JWTAuth::fromUser($kurir);

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);
    }

    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:kurirs',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $kurir = new User();
        $kurir->name = $request->input('name');
        $kurir->email = $request->input('email');
        $kurir->password = bcrypt($request->input('password'));
        $kurir->save();

        $token = JWTAuth::fromUser($kurir);

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid email or password',
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('kurir')->attempt($credentials)) {
            $kurir = Auth::guard('kurir')->user();
            $token = JWTAuth::fromUser($kurir);

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'error' => 'Invalid email or password',
        ], 401);
    }

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid email or password',
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        if (JWTAuth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'error' => 'Invalid email or password',
        ], 401);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        // return response()->json(['token' => $request->token]);

        $user = auth('api')->user();

        return response()->json(['user' => $user]);
    }

    public function getKurir(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        // return response()->json(['token' => $request->token]);

        $kurir = Auth::guard('kurir')->user();

        return response()->json(['user' => $kurir]);
    }
}
