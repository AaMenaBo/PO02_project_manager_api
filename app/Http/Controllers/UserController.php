<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(){
        try{
            $data = request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'onesignal_id' => 'string'
            ]);
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'onesignal_id' => $data['onesignal_id'] ?? null,
            ]);
            return response()->json([
                'message' => 'User created successfully',
            ], 201);

        }catch(ValidationException$e){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        }catch(QueryException $e){
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'An error occurred while creating the user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
