<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Http\Requests\AuthRequest;
use Hash;
use Auth;
use Str;
use Mail;
use App\Mail\AuthMail;
use App\Models\User;


class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {

        //dd($request);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function logout(Request $request)
    {
        //$user = User::find(Auth::user()->id);


        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }


    public function vkPut()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function vkGet()
    {
        $user = Socialite::driver('vkontakte')->stateless()->user();
        //dd($user);
        $this->reg_or_login($user);
    }


    public function googlePut()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleGet()
    {
        $user = Socialite::driver('google')->stateless()->user();
        //dd($user);
        $this->reg_or_login($user);
    }


    private function reg_or_login($user) {
        $usermodel = User::where('email', $user->email)->first();
        //dd($user);
        if(!isset($usermodel)) {
            
            $temp_pass = Str::random(10);
            $password = Hash::make($temp_pass);
            $usermodel = User::create([
                'name'=>$user->name,
                'email'=>$user->email,
                'password'=>$password,
                'register_on'=>$temp_pass,
            ]);
            //Mail::to('1stgambit@rambler.ru')->send(new AuthMail($user));
        }
        $token = $usermodel->createToken('authToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token' => $token
        ];
        return response()->json($response);
    }


    private function send_auth_mail($request) {

    }
}
