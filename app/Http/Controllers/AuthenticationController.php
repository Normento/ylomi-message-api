<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthenticationController extends Controller
{
    /**
     * User login.
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();


            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }



    /**
     * Register user.
     */
    public function store(RegisterRequest $request)
    {

       try {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['data' => $user], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong during registration.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * User Logout.
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    
}
