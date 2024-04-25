<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('AuthGuard',['except'=>['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    



    public function logout(Request $request){
        try{
            Auth::logout();
        return response()->json(['message'=>'successfully logged out']);
        }catch(\Exception $e){
            return response()->json(['message'=>'There is something wrong']);
        }
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
        ]);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),

        ]);
        return response()->json([
            'message'=>'User Created Successfully!',
            'user'=>$user,
            
        ]);
     }


     public function refresh()
     {
         return response()->json([
             'user' => Auth::user(),
             'authorisation' => [
                 'token' => Auth::refresh(),
                 'type' => 'bearer',
             ]
         ]);
     }
 }      

