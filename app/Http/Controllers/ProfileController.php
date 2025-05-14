<?php

namespace App\Http\Controllers;

use App\Models\JobLocation;
use App\Models\JobPreference;
use App\Models\JobRole;
use App\Models\JobSeekerProfile;
use App\Models\JobShift;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            ], 205);
        }
        $role_language = User::find($request->user_id);
        $role_language->type = $request->input('role');
        $role_language->language = $request->input('language');
        $role_language->update();

        return response()->json([
            'message' => "Role and Language Update Successfully"
        ], 200);
    }
    public function job_attributes()
    {
        $data = [];
        $data['job_preference'] = JobPreference::where('status', '1')->get();
        $data['job_location'] = JobLocation::where('status', '1')->get();
        $data['job_role'] = JobRole::where('status', '1')->get();
        $data['job_shift'] = JobShift::where('status', '1')->get();
        $data['job_type'] = JobType::where('status', '1')->get();
        return response()->json(['job_attributes' => $data], 200);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'mimes:jpg,jpeg,png,gif,bmp,webp,svg,tiff,ico,heic,heif',
            'resume' => 'mimes:pdf|max:5120', // 5MB max
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $JobSeekerProfile = JobSeekerProfile::where('user_id', $request->user_id);
        $JobSeekerProfile->qualification = $request->input('qualification');
        $JobSeekerProfile->experience_year = $request->input('experience_year');
        $JobSeekerProfile->expected_salary = $request->input('expected_salary');
        $JobSeekerProfile->addhar_no = $request->input('addhar_no');
        $JobSeekerProfile->job_preference = $request->input('job_preference');
        $JobSeekerProfile->job_location = $request->input('job_location');
        $JobSeekerProfile->job_role = $request->input('job_role');
        $JobSeekerProfile->job_shift = $request->input('job_shift');
        $JobSeekerProfile->job_type = $request->input('job_type');

        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('image');
            $extension = $profile_image->getClientOriginalExtension();
            $imagename = Str::random(20) . '.' . $extension;
            $profile_image->move(public_path('uploads'), $imagename);
            $JobSeekerProfile->profile_image = $imagename;
        }
        if ($request->hasFile('resume')) {
            $resume = $request->file('image');
            $extension = $resume->getClientOriginalExtension();
            $imagename = Str::random(20) . '.' . $extension;
            $resume->move(public_path('uploads'), $imagename);
            $JobSeekerProfile->resume = $imagename;
        }
        $JobSeekerProfile->update();
    }
}
