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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user');
            $table->string('template');
            $table->timestamps();

            $table->foreign('user')->references('id')->on('users');
        });

        Schema::create('personal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('physical_address');
            $table->string('phone_number');
            $table->text('personal_statement');

            $table->foreign('resume')->references('id')->on('resumes');
        });

        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume');
            $table->string('organization');
            $table->date('date_joined');
            $table->date('date_left')->nullable();
            $table->string('designation');
            $table->json('achievements');

            $table->foreign('resume')->references('id')->on('resumes');
        });

        Schema::create('education_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume');
            $table->string('institution');
            $table->date('date_joined');
            $table->date('date_left')->nullable();
            $table->string('qualification');

            $table->foreign('resume')->references('id')->on('resumes');
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume');
            $table->json('skills');

            $table->foreign('resume')->references('id')->on('resumes');
        });

        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume');
            $table->json('interests');

            $table->foreign('resume')->references('id')->on('resumes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_info');
    }
};
