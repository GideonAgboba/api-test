<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;

class UserAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }else{
            if($user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ])){
                $token = $user->createToken('TutsForWeb')->accessToken;
                return response()->json(['token' => $token], 200);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }else{
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];
     
            if (auth()->attempt($credentials)) {
                $token = auth()->user()->createToken('TutsForWeb')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
        }
    }
}