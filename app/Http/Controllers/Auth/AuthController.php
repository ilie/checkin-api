<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return AuthService::login($request);
    }

    public function logout(Request $request)
    {
        return AuthService::logout($request);
    }

    public function forgotPassword(Request $request)
    {
        return AuthService::forgotPassword($request);
    }

    public function resetPassword(Request $request)
    {
        return AuthService::resetPassword($request);
    }
}
