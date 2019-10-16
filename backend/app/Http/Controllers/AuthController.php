<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $rules = [
            'email' => 'required|email|exists:users',
            'password'  => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'email'=>'Invalid email address',
            'exists'=>'Email does not match any user'
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response($response, 200);
        } else {
            $response = ['message' => 'The given data was invalid.','errors' =>['password'=>['Incorrect password']]];
            return response($response, 422);
        }
    }

    public function register(Request $request){
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response($response, 201);
        }else{
            return response("An error occured", 422);
        }

    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    }

    public function user()
    {
        $user = User::find(Auth::user()->id)->select('name','email','points');

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
