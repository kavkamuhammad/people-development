<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingPeserta extends Model
{
    use HasFactory;

    protected $table = 'training_peserta';

    protected $fillable = [
        'training_id',
        'employee_id',
        'pretest_benar',
        'posttest_benar',
        'skor_pretest',
        'skor_posttest',
        'n_gain',
        'kategori_n_gain',
        'persentase_kenaikan',
        'status_peserta',
        'catatan',
    ];

    protected $casts = [
        'skor_pretest' => 'decimal:2',
        'skor_posttest' => 'decimal:2',
        'n_gain' => 'decimal:2',
        'persentase_kenaikan' => 'decimal:2',
    ];

    /* ============================
       RELATIONS
    ============================ */

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluasiAtasan()
    {
        return $this->hasOne(EvaluasiAtasan::class, 'peserta_id');
    }

    public function evaluasiTrainer()
    {
        return $this->hasOne(EvaluasiTrainer::class, 'peserta_id');
    }


    /* ============================
       BOOT: AUTO CALCULATE SCORES
    ============================ */

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $jumlahSoal = $model->training->jumlah_soal ?? 0;

            if ($jumlahSoal > 0) {

                // Hitung Skor Pretest & Posttest (0 - 100)
                $model->skor_pretest = ($model->pretest_benar / $jumlahSoal) * 100;
                $model->skor_posttest = ($model->posttest_benar / $jumlahSoal) * 100;

                // Hitung N-Gain
                if ($model->skor_pretest < 100) {
                    $model->n_gain = ($model->skor_posttest - $model->skor_pretest) / (100 - $model->skor_pretest);
                } else {
                    $model->n_gain = 0;
                }

                // Kategori N-Gain
                if ($model->n_gain >= 0.7) {
                    $model->kategori_n_gain = 'Tinggi';
                } elseif ($model->n_gain >= 0.3) {
                    $model->kategori_n_gain = 'Sedang';
                } else {
                    $model->kategori_n_gain = 'Rendah';
                }

                // Persentase Kenaikan
                if ($model->skor_pretest > 0) {
                    $model->persentase_kenaikan =
                        (($model->skor_posttest - $model->skor_pretest) / $model->skor_pretest) * 100;
                } else {
                    $model->persentase_kenaikan = $model->skor_posttest > 0 ? 100 : 0;
                }
            }
        });
    }
}
