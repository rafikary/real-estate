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
        Schema::create('m_landbank', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('loc');
            $table->integer('prov_id');
            $table->integer('city_id');
            $table->integer('district_id');
            $table->string('postcode')->nullable();
            $table->string('latitiude')->nullable(); // matching user's spelling "latitiude"
            $table->string('longitude')->nullable();
            $table->string('limit_n')->nullable();
            $table->string('limit_e')->nullable();
            $table->string('limit_s')->nullable();
            $table->string('limit_w')->nullable();
            
            $table->string('cert_type');
            $table->string('cert_no');
            $table->string('nib')->nullable();
            $table->string('owner')->nullable();
            $table->decimal('area_cert', 12, 2);
            $table->decimal('area_real', 12, 2);
            $table->date('cert_date')->nullable();
            $table->date('cert_date_to')->nullable();
            $table->string('reference')->nullable();
            $table->string('image')->nullable();
            
            // Statuses and audit
            $table->string('status_doc')->default('Draft'); // Draft, In Approval, Approved, Reject, Revisi
            $table->string('status')->default('Aktif'); // Aktif, Non-Aktif
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('m_landbank_d_hist', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('m_landbank_id');
            $table->foreign('m_landbank_id')->references('id')->on('m_landbank')->cascadeOnDelete();
            $table->integer('seq');
            $table->string('type');
            $table->string('cert_type');
            $table->string('cert_no');
            $table->string('nib')->nullable();
            $table->string('owner')->nullable();
            $table->decimal('area_cert', 12, 2);
            $table->decimal('area_real', 12, 2);
            $table->date('cert_date')->nullable();
            $table->date('cert_date_to')->nullable();
            $table->string('reference')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_landbank_d_hist');
        Schema::dropIfExists('m_landbank');
    }
};
