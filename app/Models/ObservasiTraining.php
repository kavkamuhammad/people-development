<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservasiTraining extends Model
{
    use HasFactory;

    protected $table = 'observasi_training';

    protected $fillable = [
        'user_id',
        'training_id',
        'trainer_id',
        'materi_training',
        'tanggal_training',
        'apersepsi',
        'menyampaikan_tujuan_pembelajaran',
        'learning_brainstorming',
        'modelling',
        'inquiry_learning_community',
        'inquiry',
        'learning_community',
        'authentic_assesment',
        'presentasi_hasil_diskusi',
        'kesempatan_menanggapi_presentasi',
        'klarifikasi_hasil_presentasi',
        'konstruktivis_reflection',
        'catatan',
    ];

    protected $casts = [
        'tanggal_training' => 'date',
    ];

    // ========== RELATIONSHIPS ==========
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Get all aspek penilaian fields
     */
    public static function getAspekPenilaian()
    {
        return [
            'apersepsi' => 'Apersepsi',
            'menyampaikan_tujuan_pembelajaran' => 'Menyampaikan Tujuan Pembelajaran',
            'learning_brainstorming' => 'Learning Brainstorming',
            'modelling' => 'Modelling',
            'inquiry_learning_community' => 'Inquiry Learning Community',
            'inquiry' => 'Inquiry',
            'learning_community' => 'Learning Community',
            'authentic_assesment' => 'Authentic Assessment',
            'presentasi_hasil_diskusi' => 'Presentasi Hasil Diskusi',
            'kesempatan_menanggapi_presentasi' => 'Kesempatan Menanggapi Presentasi',
            'klarifikasi_hasil_presentasi' => 'Klarifikasi Hasil Presentasi',
            'konstruktivis_reflection' => 'Konstruktivis Reflection',
        ];
    }

    /**
     * Get options for aspek penilaian
     */
    public static function getPenilaianOptions()
    {
        return [
            'Ada' => 'Ada',
            'Tidak' => 'Tidak'
        ];
    }

    /**
     * Count total 'Ada' for this observation
     */
    public function getTotalAdaAttribute()
    {
        $count = 0;
        foreach (array_keys(self::getAspekPenilaian()) as $aspek) {
            if ($this->$aspek === 'Ada') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Count total 'Tidak' for this observation
     */
    public function getTotalTidakAttribute()
    {
        $count = 0;
        foreach (array_keys(self::getAspekPenilaian()) as $aspek) {
            if ($this->$aspek === 'Tidak') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Get percentage of 'Ada'
     */
    public function getPercentageAdaAttribute()
    {
        $total = count(self::getAspekPenilaian());
        return $total > 0 ? round(($this->total_ada / $total) * 100, 1) : 0;
    }

    /**
     * Check if training already has observation
     */
    public static function hasObservation($trainingId)
    {
        return self::where('training_id', $trainingId)->exists();
    }
}
