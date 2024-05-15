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
        Schema::create('labour', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gender_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('taluka_id');
            $table->unsignedBigInteger('village_id');
            $table->unsignedBigInteger('skill_id');
            $table->string('user_type')->default('null');
            $table->string('full_name');
            $table->string('date_of_birth');
            $table->string('mobile_number');
            $table->string('landline_number')->nullable();
            $table->string('mgnrega_card_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('is_approved')->default(true);
            $table->integer('is_resubmitted')->default(false);
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->string('other_remark')->default('null');
            $table->string('sync_reason')->default('null');
            $table->string('aadhar_image')->default('null');
            $table->string('mgnrega_image')->default('null');
            $table->string('profile_image')->default('null');
            $table->string('voter_image')->default('null');
            $table->integer('is_active')->default(true);
            $table->integer('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour');
    }
};
