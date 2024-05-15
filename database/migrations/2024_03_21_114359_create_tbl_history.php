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
        Schema::create('tbl_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roles_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('labour_id');
            $table->unsignedBigInteger('is_approved');
            $table->unsignedBigInteger('reason_id');
            $table->string('other_remark')->default('null');
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
        Schema::dropIfExists('tbl_history');
    }
};
