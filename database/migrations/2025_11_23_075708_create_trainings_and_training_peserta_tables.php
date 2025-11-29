<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Training (Header)
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
            $table->foreignId('materi_training_id')->constrained('materi_trainings')->cascadeOnDelete();
            $table->date('tanggal_training');
            $table->integer('jumlah_soal')->default(0);
            $table->string('jenis_training')->nullable(); // Internal/Eksternal
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Training Peserta (Detail)
        Schema::create('training_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            
            // Pretest & Posttest
            $table->integer('pretest_benar')->default(0);
            $table->integer('posttest_benar')->default(0);
            
            // Auto calculated fields
            $table->decimal('skor_pretest', 5, 2)->default(0);
            $table->decimal('skor_posttest', 5, 2)->default(0);
            $table->decimal('n_gain', 5, 2)->nullable();
            $table->string('kategori_n_gain', 50)->nullable();
            $table->decimal('persentase_kenaikan', 5, 2)->nullable();
            
            // Status peserta
            $table->string('status_peserta')->default('Lulus'); // Lulus/Tidak Lulus
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_peserta');
        Schema::dropIfExists('trainings');
    }
};