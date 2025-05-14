<?php

namespace App\Http\Controllers;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 205);
        }

        $register = new User;
        $register->name = $request->input('name');
        $register->mobile = $request->input('mobile');
        $register->email = $request->input('email');
        $register->otp = rand(1111, 9999);
        $register->save();
        $email = $register->email;
        $htmlContent = "<p>OTP : <strong>{$register->otp}</strong></p>";

        Mail::html($htmlContent, function ($message) use ($email) {
            $message->to($email)
                ->subject('OTP');
        });
        JobSeekerProfile::insert(['user_id' => $register->id]);
        
        return response()->json(['user_id' => $register->id, 'mobile' => $register->mobile,'otp'=>$register->otp], 200);
    }
    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $verifyOtp = User::where('mobile', $request->mobile)->first();
        if ($verifyOtp->otp == $request->otp) {
            return response()->json([
                'success' => true,
                'message' => "Otp Matched",
                'user_id' => $verifyOtp->id,
                'token' => Crypt::encryptString($verifyOtp->id),
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'error' => "Invalid OTP",
            ], 201);
        }
    }
}
