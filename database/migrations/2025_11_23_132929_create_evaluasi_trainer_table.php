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
        Schema::create('evaluasi_trainer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('training_peserta')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->string('materi_training');
            $table->date('tanggal_training');
            
            // Aspek Penilaian (SB = Sangat Baik, B = Baik, C = Cukup, K = Kurang)
            $table->enum('relevansi_materi', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('pemahaman_materi', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('penguasaan_trainer', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('penyampaian_trainer', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('fasilitas', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('manfaat_keseluruhan', ['SB', 'B', 'C', 'K'])->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['training_id', 'peserta_id']);
            $table->index('trainer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_trainer');
    }
};