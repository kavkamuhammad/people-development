<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'materi_training_id',
        'tanggal_training',
        'jumlah_soal',
        'jenis_training',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_training' => 'date',
    ];

    /**
     * Relasi ke Trainer
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Relasi ke Materi Training
     */
    public function materiTraining()
    {
        return $this->belongsTo(MateriTraining::class, 'materi_training_id');
    }

    /**
     * Relasi ke Peserta Training
     */
    public function peserta()
    {
        return $this->hasMany(TrainingPeserta::class, 'training_id');
    }

    /**
     * Relasi ke Evaluasi Trainer
     */
    public function evaluasiTrainer()
{
    return $this->hasMany(EvaluasiTrainer::class, 'training_id');
}

    /**
     * Relasi ke Evaluasi Atasan
     */
    public function evaluasiAtasan()
    {
        return $this->hasMany(EvaluasiAtasan::class, 'training_id');
    }

    /**
     * Relasi ke Observasi Training
     */
    public function observasiTraining()
    {
        return $this->hasOne(ObservasiTraining::class, 'training_id');
    }

    /**
     * Helper: Cek apakah peserta sudah melakukan evaluasi trainer
     * 
     * @param int $employeeId
     * @return bool
     */
    public function getPesertaBelumEvaluasi($employeeId)
    {
        return $this->peserta()
            ->where('employee_id', $employeeId)
            ->whereDoesntHave('evaluasiTrainer')
            ->exists();
    }

    /**
     * Scope: Training yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_training', '>=', now());
    }

    /**
     * Scope: Training yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('tanggal_training', '<', now());
    }

    /**
     * Accessor: Format tanggal Indonesia
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal_training->format('d F Y');
    }

    /**
     * Helper: Hitung total peserta
     */
    public function getTotalPesertaAttribute()
    {
        return $this->peserta()->count();
    }

    /**
     * Helper: Hitung rata-rata N-Gain
     */
    public function getAverageNGainAttribute()
    {
        return $this->peserta()->avg('n_gain') ?? 0;
    }
}
