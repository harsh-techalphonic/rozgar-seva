<?php

namespace App\Http\Controllers;

use App\Models\JobLocation;
use App\Models\JobPreference;
use App\Models\JobRole;
use App\Models\JobShift;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function role_and_language(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role' => 'required',
            'language' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $role_language = User::find($request->user_id);
        $role_language->type = $request->input('role');
        $role_language->language = $request->input('language');
        $role_language->update();

        return response()->json([
            'message' => "Role and Language Update Successfully"
        ], 200);
    }
    public function job_attributes(Request $request)
    {
        $data = [];
        $data['job_preference'] = JobPreference::where('status', '1')->get();
        $data['job_location'] = JobLocation::where('status', '1')->get();
        $data['job_role'] = JobRole::where('status', '1')->get();
        $data['job_shift'] = JobShift::where('status', '1')->get();
        $data['job_type'] = JobType::where('status', '1')->get();
        return response()->json(['job_attributes' => $data], 200);
    }
}
