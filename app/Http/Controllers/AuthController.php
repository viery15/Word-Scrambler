<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) {
            return redirect()->route('history');
        }
        return view('Admin.login');
    }

    public function login(Request $request)
    {

        $data = [
            'username'     => $request->username,
            'password'  => $request->password,
        ];

        Auth::attempt($data);

        if (Auth::check()) {
            $res['status'] = "S";
            $res['message'] = "Login Success";
        }
        else {
            $res['status'] = "E";
            $res['message'] = "Login Faild";
        }

        return response()->json($res);

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
