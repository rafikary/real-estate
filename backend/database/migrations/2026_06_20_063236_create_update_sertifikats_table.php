<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_update')->unique();             // Auto: UPS-001

            // Relasi ke land bank
            $table->uuid('land_bank_id');
            $table->foreign('land_bank_id')
                  ->references('id')
                  ->on('m_landbank')
                  ->cascadeOnDelete();

            // Jenis perubahan dari flowchart
            $table->string('jenis_perubahan');                  // Balik Nama, Perubahan Jenis Hak, Perpanjang Masa Sertifikat, Penggabungan Tanah, Pemisahan Tanah
            $table->string('no_perubahan')->nullable();
            $table->date('tgl_perubahan')->nullable();
            $table->text('keterangan')->nullable();

            // Data sertifikat baru (hasil update)
            $table->string('jenis_sertifikat_baru')->nullable();
            $table->string('no_sertifikat_baru')->nullable();
            $table->string('nib_baru')->nullable();
            $table->string('pemilik_baru')->nullable();
            $table->decimal('luas_sertifikat_baru', 12, 2)->nullable();
            $table->date('tanggal_terbit_baru')->nullable();
            $table->date('masa_berlaku_baru')->nullable();

            // Instansi/notaris yang mengesahkan
            $table->string('nama_notaris')->nullable();
            $table->string('no_akta')->nullable();

            // Attachment
            $table->string('lampiran_path')->nullable();

            // Status
            $table->string('status_doc')->default('Draft');     // Draft, In Approval, Approved, Reject, Revisi
            $table->string('status')->default('Aktif');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_sertifikats');
    }
};
