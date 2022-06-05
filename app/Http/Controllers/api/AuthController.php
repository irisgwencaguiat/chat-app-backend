<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|unique:users|email|string",
            "username" => "required|unique:users|string",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        User::create([
            "email" => $request->input("email"),
            "username" => $request->input("username"),
            "password" => bcrypt($request->input("password")),
        ]);

        $credentials = [
            "email" => $request->input("email"),
            "password" => $request->input("password"),
        ];

        if (!Auth::attempt($credentials)) {
            return customResponse()
                ->data([])
                ->message("Failed to sign up.")
                ->unathorized()
                ->generate();
        }

        $accessToken = Auth::user()->createToken("authToken")->accessToken;
        $user = User::where("id", Auth::id())
            ->get()
            ->first();

        return customResponse()
            ->data([
                "user" => $user,
                "access_token" => $accessToken,
            ])
            ->message("You have successfully signed up.")
            ->success()
            ->generate();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "login" => "required|string",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $field = filter_var($request->input("login"), FILTER_VALIDATE_EMAIL)
            ? "email"
            : "username";

        $credentials = [
            $field => $request->input("login"),
            "password" => $request->input("password"),
        ];

        if (!Auth::attempt($credentials)) {
            return customResponse()
                ->data([])
                ->message("Invalid credentials.")
                ->unathorized()
                ->generate();
        }
        $accessToken = Auth::user()->createToken("authToken")->accessToken;
        $user = User::where("id", Auth::id())
            ->get()
            ->first();

        return customResponse()
            ->data([
                "user" => $user,
                "access_token" => $accessToken,
            ])
            ->message("You have successfully logged in.")
            ->success()
            ->generate();
    }
}
