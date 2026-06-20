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
        Schema::create('m_item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('type_id');
            $table->integer('group_id');
            $table->string('code');
            $table->string('name');
            $table->integer('uom_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_item');
    }
};
