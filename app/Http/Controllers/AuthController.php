<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    //


    public function register(Request $request) {


        try{

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
        ]);
    
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
                return response()->json(['token' => $token, 'user' => $user], 201);

        }catch(Exception $error){

                return response()->json([
                    'error' => $error->getMessage()
                ], 400);
    }

       
    }
    
    public function login(Request $request) {

        try{

            $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            ]);
    
            $user = User::where('email', $validated['email'])->first();
        
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
                return response()->json(['token' => $token, 'user' => $user], 200);

        }catch(Exception $error){

                return response()->json([
                    'error' => $error->getMessage()
                ], 400);
    }
        
    }

    
    public function logout(Request $request){ 

        try{
     
            $request->user()->tokens()->delete();


            return response()->json([
                'message' => 'User logged out successfully'
            ]);

        }catch(Exception $error){

            return response()->json([
                'error' => $error->getMessage()
            ], 400);
    };

    }
}
