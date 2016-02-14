<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Response;
use App\User;

class UserController extends Controller
{
    private function generateToken($password) {
        return md5($password . time());
    }
    //
    public function createUser(Request $request) {
        if(!$request->name || !$request->email || !$request->password) {
            return Response::json([
                "status" => "NOT_OK",
                "response" => "Fields required: \"name\", \"email\", \"password\""
            ], 400);
        }
        else {
            $user = User::where('email', $request->email)->first();
            if($user) {
                return Response::json([
                    "status" => "NOT_OK",
                    "response" => "This email is already registered!"
                ], 409);
            }

            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => md5($request->password),
            ]);
            if($request->admin_flag) {
                $user->admin_flag = $request->admin_flag;
            }
            $user->remember_token = $this->generateToken(md5($request->password));
            $user->save();

            return Response::json([
                "status" => "OK",
                "response" => $user,
                "token" => $user->remember_token
            ], 200)->header('Access-Control-Allow-Origin', '*');
        }
    }

    public function loginUser(Request $request) {
        if(!$request->email || !$request->password) {
            return Response::json([
                "status" => "NOT_OK",
                "response" => "Fields required: \"email\", \"password\""
            ], 400);
        }

        $user = User::where('email', $request->email)->where('password', md5($request->password))->first();
        if(!$user) {
            return Response::json([
                "status" => "NOT_OK",
                "response" => "User not found!"
            ], 404);
        }

        $user->remember_token = $this->generateToken($user->password);
        $user->save();

        return Response::json([
            "status" => "OK",
            "response" => $user,
            "token" => $user->remember_token
        ], 200)->header('Access-Control-Allow-Origin', '*');
    }

	public function userEvents(Request $request) {
		return Response::json([
			"status" => "OK",
			"response" => User::where('remember_token', $request->token)->with('registered')->with('admin')->first()
		]);
	
	}

}
