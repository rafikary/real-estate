<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siteplans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_siteplan')->unique();           // Auto: SPL-001

            // Relasi ke Master Project
            $table->foreignId('master_project_id')
                  ->constrained('master_projects')
                  ->cascadeOnDelete();

            $table->string('nama_siteplan');
            $table->text('deskripsi')->nullable();

            // Tipe unit
            $table->string('tipe_unit');                        // Rumah, Ruko, Kavling, Apartemen, Gudang
            $table->string('cluster')->nullable();              // Nama cluster/blok

            // Dimensi
            $table->decimal('luas_tanah', 10, 2)->default(0);  // m²
            $table->decimal('luas_bangunan', 10, 2)->default(0); // m²
            $table->integer('jumlah_lantai')->default(1);

            // Produksi & harga
            $table->integer('jumlah_unit')->default(0);
            $table->decimal('harga_dasar', 18, 2)->default(0); // Rp

            // Spesifikasi tambahan
            $table->integer('kamar_tidur')->nullable();
            $table->integer('kamar_mandi')->nullable();
            $table->string('orientasi')->nullable();            // Utara, Selatan, Timur, Barat

            // Attachment (gambar denah)
            $table->string('lampiran_path')->nullable();

            // Status
            $table->string('status_doc')->default('Draft');
            $table->string('status')->default('Aktif');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siteplans');
    }
};
