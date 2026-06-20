<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('split_grouping_lands', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();          // Auto: SPG-001

            $table->enum('tipe', ['Split', 'Grouping']);        // Split = pecah, Grouping = gabung
            $table->date('tgl_transaksi');

            // Land bank sumber (satu untuk Split, satu untuk Grouping)
            $table->uuid('land_bank_id_sumber');
            $table->foreign('land_bank_id_sumber')
                  ->references('id')
                  ->on('m_landbank')
                  ->cascadeOnDelete();

            // Land bank hasil (untuk Split → multiple, untuk Grouping → satu baru)
            // Disimpan sebagai JSON array dari land_bank_ids
            $table->json('land_bank_ids_hasil')->nullable();

            $table->text('keterangan')->nullable();
            $table->string('nama_notaris')->nullable();
            $table->string('no_akta')->nullable();
            $table->string('lampiran_path')->nullable();

            $table->string('status_doc')->default('Draft');
            $table->string('status')->default('Aktif');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('split_grouping_lands');
    }
};
