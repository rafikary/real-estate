<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_projects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_proyek')->unique();                     // Auto: PRJ-001
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();

            // Relasi ke land bank (optional)
            $table->uuid('land_bank_id')->nullable();
            $table->foreign('land_bank_id')
                  ->references('id')
                  ->on('m_landbank')
                  ->nullOnDelete();

            // Lokasi
            $table->string('provinsi');
            $table->string('kota_kabupaten');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->text('alamat_lengkap')->nullable();

            // Info proyek
            $table->string('tipe_proyek');          // Perumahan, Komersial, Mixed-Use, Industri, dll
            $table->decimal('luas_total', 12, 2)->default(0);     // m²
            $table->decimal('nilai_investasi', 18, 2)->default(0); // Rupiah
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai_estimasi')->nullable();

            // Developer / PIC
            $table->string('nama_developer')->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('no_telepon_pic')->nullable();

            // Attachment
            $table->string('lampiran_path')->nullable();

            // Status
            $table->string('status_doc')->default('Draft');   // Draft, In Approval, Approved, Reject, Revisi
            $table->string('status')->default('Aktif');       // Aktif, Non-Aktif

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_projects');
    }
};
