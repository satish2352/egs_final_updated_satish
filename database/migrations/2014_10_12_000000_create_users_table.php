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
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('email')->unique();
            // $table->string('u_uname');
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->string('f_name');
            $table->string('m_name');
            $table->string('l_name');
            $table->string('number');
            $table->string('device_id')->default('null');
            $table->string('aadhar_no');
            $table->string('address');
            $table->integer('state');
            $table->integer('district');
            $table->integer('taluka');
            $table->integer('village');
            $table->string('pincode');
            $table->bigInteger('user_type')->nullable();
            $table->integer('user_district');
            $table->integer('user_taluka');
            $table->integer('user_village');
            $table->string('ip_address');
            $table->string('user_agent')->default('null');
            $table->string('user_profile')->default('null');
            // $table->rememberToken();
            $table->text('remember_token', 255)->nullable();
            $table->integer('is_active')->default(true);
            $table->integer('is_deleted')->default(false);
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
