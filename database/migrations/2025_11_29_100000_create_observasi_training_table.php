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
        Schema::create('observasi_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->string('materi_training');
            $table->date('tanggal_training');
            
            // Aspek Penilaian - Value: 'Ada' atau 'Tidak'
            $table->enum('apersepsi', ['Ada', 'Tidak'])->nullable();
            $table->enum('menyampaikan_tujuan_pembelajaran', ['Ada', 'Tidak'])->nullable();
            $table->enum('learning_brainstorming', ['Ada', 'Tidak'])->nullable();
            $table->enum('modelling', ['Ada', 'Tidak'])->nullable();
            $table->enum('inquiry_learning_community', ['Ada', 'Tidak'])->nullable();
            $table->enum('inquiry', ['Ada', 'Tidak'])->nullable();
            $table->enum('learning_community', ['Ada', 'Tidak'])->nullable();
            $table->enum('authentic_assesment', ['Ada', 'Tidak'])->nullable();
            $table->enum('presentasi_hasil_diskusi', ['Ada', 'Tidak'])->nullable();
            $table->enum('kesempatan_menanggapi_presentasi', ['Ada', 'Tidak'])->nullable();
            $table->enum('klarifikasi_hasil_presentasi', ['Ada', 'Tidak'])->nullable();
            $table->enum('konstruktivis_reflection', ['Ada', 'Tidak'])->nullable();
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Unique constraint: satu training hanya bisa diobservasi sekali
            $table->unique('training_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observasi_training');
    }
};
