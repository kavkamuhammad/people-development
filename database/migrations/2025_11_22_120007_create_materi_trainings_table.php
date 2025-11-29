<?php
// database/migrations/2025_11_22_000002_create_materi_trainings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_materi')->unique();
            $table->string('nama_materi');
            $table->string('jenis_materi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_trainings');
    }
};
