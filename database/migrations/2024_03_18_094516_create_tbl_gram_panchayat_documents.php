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
        Schema::create('tbl_gram_panchayat_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('document_type_id');
            $table->string('document_name');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('document_pdf')->default('null');
            $table->integer('is_approved')->default(true);
            $table->integer('is_resubmitted')->default(false);
            $table->integer('reason_doc_id')->nullable();
            $table->string('other_remark')->nullable();
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
        Schema::dropIfExists('tbl_gram_panchayat_documents');
    }
};
