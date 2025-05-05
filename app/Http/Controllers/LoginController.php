<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|mobile|unique:users,mobile',
        ]);
        $register = new User;
        $register->name = $request->input('name');
        $register->mobile = $request->input('mobile');
        $register->email = $request->input('email');
        $register->otp = rand(0000, 9999);
        $register->save();
        return response()->json([], 200);
    }
}
