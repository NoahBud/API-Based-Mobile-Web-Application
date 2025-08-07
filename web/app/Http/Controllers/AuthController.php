<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest; 
Use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function register(UserRequest $request){
        $validated = $request->validated();

        $user = User::create($validated);

        $token = $user->createToken($request->name);


        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        //Auth::attempt(['email' => $email, 'password' => $password])
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)){ //value , hashVAlue
            return [
                'message' => 'Identifiants incorrects.'
            ];
        } 

        $token = $user->createToken($user->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];

    }

    // on veut supprimer les tokens de l'utilisateur quand il se logout
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return [
            'message' => 'Vous êtes déconnectés'
        ]; 
    }
}
