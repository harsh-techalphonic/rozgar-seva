<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {   
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('profile_image')->nullable();
            $table->string('resume')->nullable();
            $table->string('qualification')->nullable();
            $table->string('experience_year')->nullable();
            $table->string('expected_salary')->nullable();
            $table->integer('addhar_no')->nullable();
            $table->integer('job_preference')->nullable();
            $table->integer('job_location')->nullable();
            $table->integer('job_role')->nullable();
            $table->integer('job_shift')->nullable();
            $table->integer('job_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker_profiles');
    }
};
