<?php
// app/Models/Trainer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $table = 'trainers';

    protected $fillable = [
        'kode_trainer',
        'nama_trainer',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relasi ke EvaluasiTrainer
    public function evaluasi()
    {
        return $this->hasMany(EvaluasiTrainer::class, 'trainer_id');
    }

    // Method untuk mendapatkan average rating
    // PERBAIKAN: Menggunakan nama kolom yang benar dari tabel evaluasi_trainer
    // Konversi enum (SB, B, C, K) ke numeric (4, 3, 2, 1)
    public function averageRating()
    {
        return $this->evaluasi()
            ->selectRaw('AVG((
                CASE penguasaan_trainer
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END +
                CASE penyampaian_trainer
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END +
                CASE pemahaman_materi
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END +
                CASE manfaat_keseluruhan
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END
            ) / 4) as avg_rating')
            ->value('avg_rating') ?? 0;
    }

    // Relasi ke Training
    public function trainings()
    {
        return $this->hasMany(Training::class, 'trainer_id');
    }

    // Scope untuk trainer aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessor untuk nama lengkap dengan kode
    public function getFullNameAttribute()
    {
        return "{$this->kode_trainer} - {$this->nama_trainer}";
    }
}