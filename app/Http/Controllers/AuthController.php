<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthController extends Controller
{
    // Registrera användare (POST)
    public function register(Request $request)
    {
        // Validera data
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'is_admin' => 'nullable|boolean',
                'bio' => 'nullable|string|max:255',
                'current_read' => 'nullable|string|max:255',
                'avatar_file' => 'nullable|string',
                'avatar_url' => 'nullable|string'
            ]
        );

        // Misslyckad validering
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validator->errors()
            ], 401);
        }

        // Ingen användare autentiserad
        if (!$request->user()) {
            $isA = $request->input('is_admin'); // läs in is_admin från input
            //Om is_admin skickats med i anropet && är sant (true|1)
            if ($isA && filter_var($isA, FILTER_VALIDATE_BOOLEAN)) {
                return response()->json([
                    'message' => 'Endast administrtörer kan skapa admins'
                ], 403);
            } else {
                // Ingen användare autentiserad och inget is_admin/ falskt is_admin skickat
                // Skapa ny användare
                $newUser = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']),
                    'is_admin' => false,
                    'bio' => $request['bio'] ?? null,
                    'avatar_file' => $request['avatar_file'] ?? null,
                    'avatar_url' => $request['avatar_url'] ?? null
                ]);

                // Skapa token
                $token = $newUser->createToken('auth_token')->plainTextToken;

                $response = [
                    'message' => 'Användare registrerad',
                    'user' => $newUser,
                    'token' => $token
                ];

                return response($response, 201); // return response created
            }
        }

        if ($request->user()->isAdmin()) {
            // admin kan skapa andra admins
            $adm = $request->has('is_admin') ? $request->is_admin : false; // om is_admin inte skickas sätt till false

            $newUser = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'is_admin' => $adm,
                'bio' => $request['bio'] ?? null,
                'avatar_file' => $request['avatar_file'] ?? null,
                'avatar_url' => $request['avatar_url'] ?? null
            ]);

            // Skapa token
            $token = $newUser->createToken('auth_token')->plainTextToken;

            $response = [
                'message' => 'Användare registrerad',
                'user' => $newUser,
                'token' => $token
            ];

            return response($response, 201); // return response created

        }
    }

    // Logga in användare
    public function login(Request $request)
    {
        $validatedUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        // Misslyckad validering
        if ($validatedUser->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validatedUser->errors()
            ], 401);
        }

        // Felaktig credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'Failed credentials',
                'message' => 'Incorrect email and/or password'
            ], 401);
        }

        // Korrekt inloggning - returnera token
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'message' => 'User logged in!',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = [
            'message' => 'User logged out!'
        ];
        return response($response, 200);
    }
}
