<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        try{

            return response()->json(User::all(), 200);

        }catch(Exception $error){

            return response()->json([
                'error' => $error->getMessage()
            ], 400);
    }

        

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

    
            $user = User::create([
                'name' => $validator['name'],
                'email' => $validator['email'],
                'password' => bcrypt($validator['password']),
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
                return response()->json(['token' => $token, 'user' => $user], 201);

        }catch(Exception $error){

                return response()->json([
                    'error' => $error->getMessage()
                ], 400);
    }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 200);
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //


        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
        ];
        
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }
        
        $user->update($updateData);
        


            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Obtén al usuario autenticado
        $user = $request->user();
    
        // Si el usuario no existe
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Elimina el usuario
        $user->delete();
    
        // Devuelve la respuesta
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
    

    public function statistics() {
        return response()->json([
            'daily' => User::whereNotNull('created_at')->whereDate('created_at', Carbon::today())->count(),
            'weekly' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'monthly' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ], 200);
    }



    
}
