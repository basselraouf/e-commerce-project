<?php

namespace App\Http\Controllers;

use App\Http\traits\GeneralTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    use GeneralTraits;
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
           return $this->returnError(errNum:'E001' , msg:'Unauthorized');
        }
        $user = Auth::user();
        $user->token=$token;
        return $this->returnData(key:"user data", value:$user, msg:"This is user data");
    }
    
    public function logout(Request $request){
        Auth::logout();
        return $this->returnSucessMessage( msg:'successfully logged out');
    }

    public function register(Request $request){
        try{
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
            return $this->returnData(key:'user data', value:$user, msg:'User Created Successfully!');
        }catch(\Exception $e){
            return $this->returnError(errNum:'E005' , msg:'There is something wrong');
        }
    }

     public function refresh(){     
        return $this->returnData(key:'refresh token', value:Auth::refresh(), msg:'New token generated');
     }
 }      

