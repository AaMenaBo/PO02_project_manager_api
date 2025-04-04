<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    //Register_API - nam, email, password, OneSignal ID
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|',
            ]);
            $user = User::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
            ], 201);


        } catch (ValidationException $th) {
            return response()->json([
                'status' => false,
                'message' => $th->validator->errors()->first(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status' => false,
                'message' => 'A error occurred in the database',
            ], 500);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    //Login_API
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);
            //Check if the user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            //Password check
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }
            //Create Token
            $token = $user->createToken('sanctum_token')->plainTextToken;

            //Update OneSignal ID - IMPLEMENT THIS

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token,
            ], 200);
        } catch (ValidationException $th) {
            return response()->json([
                'status' => false,
                'message' => $th->validator->errors()->first(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status' => false,
                'message' => 'A error occurred in the database',
            ], 500);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
    //Profile_API
    public function profile()
    {
        $userdata = auth()->user();
        return response()->json([
            'status' => true,
            'message' => 'User data',
            'data' => [
                'id' => $userdata->id,
                'name' => $userdata->name,
                'email' => $userdata->email,
                'onesignal_id' => $userdata->onesignal_id,
            ],
        ], 200);
    }
    //Logout_API
    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 200);
    }
}
