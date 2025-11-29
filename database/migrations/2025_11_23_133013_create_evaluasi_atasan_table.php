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
        Schema::create('evaluasi_atasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('training_peserta')->onDelete('cascade');
            $table->foreignId('atasan_id')->constrained('users')->onDelete('cascade');
            
            // Data Training
            $table->string('nama_karyawan');
            $table->string('materi_training');
            $table->string('department'); // ✅ DIPERBAIKI
            $table->date('tanggal_training');
            
            // Aspek Penilaian (Skor 1-5)
            $table->integer('peningkatan_keterampilan')->nullable();
            $table->text('uraian_peningkatan_keterampilan')->nullable();
            
            $table->integer('penerapan_ilmu')->nullable();
            $table->text('uraian_penerapan_ilmu')->nullable();
            
            $table->integer('perubahan_perilaku')->nullable();
            $table->text('uraian_perubahan_perilaku')->nullable();
            
            $table->integer('dampak_performa')->nullable();
            $table->text('uraian_dampak_performa')->nullable();
            
            // Total dan Kategori (Auto-calculated)
            $table->integer('total_skor')->nullable();
            $table->enum('kategori', ['Sangat Baik', 'Baik', 'Cukup', 'Perlu Perbaikan'])->nullable();
            
            // Catatan Atasan
            $table->text('catatan_atasan')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['training_id', 'peserta_id']);
            $table->index('atasan_id');
            $table->index('department'); // ✅ DIPERBAIKI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_atasan');
    }
};