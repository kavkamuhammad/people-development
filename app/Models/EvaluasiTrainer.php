<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiTrainer extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_trainer';

    protected $fillable = [
        'training_id',
        'peserta_id',
        'trainer_id',
        'materi_training',
        'tanggal_training',
        'relevansi_materi',
        'pemahaman_materi',
        'penguasaan_trainer',
        'penyampaian_trainer',
        'fasilitas',
        'manfaat_keseluruhan',
    ];

    protected $casts = [
        'tanggal_training' => 'date',
    ];

    // ========== RELATIONSHIPS ==========
    
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function peserta()
    {
        return $this->belongsTo(TrainingPeserta::class, 'peserta_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Get indikator options
     */
    public static function getIndikatorOptions()
    {
        return [
            'SB' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            'K' => 'Kurang'
        ];
    }

    /**
     * Get poin value for indikator
     */
    public static function getIndikatorPoin($indikator)
    {
        $poin = [
            'SB' => 4,
            'B' => 3,
            'C' => 2,
            'K' => 1
        ];
        
        return $poin[$indikator] ?? 0;
    }

    /**
     * Get statistics by training ID
     * Returns percentage and count for each aspect and indicator
     */
    public static function getStatistikByTraining($trainingId)
    {
        $evaluasi = self::where('training_id', $trainingId)->get();
        
        $aspects = [
            'relevansi_materi',
            'pemahaman_materi',
            'penguasaan_trainer',
            'penyampaian_trainer',
            'fasilitas',
            'manfaat_keseluruhan'
        ];
        
        $stats = [];
        foreach ($aspects as $aspect) {
            $counts = $evaluasi->countBy($aspect);
            $total = $evaluasi->count();
            
            $stats[$aspect] = [
                'SB' => [
                    'count' => $counts['SB'] ?? 0,
                    'percentage' => $total > 0 ? round((($counts['SB'] ?? 0) / $total) * 100) : 0
                ],
                'B' => [
                    'count' => $counts['B'] ?? 0,
                    'percentage' => $total > 0 ? round((($counts['B'] ?? 0) / $total) * 100) : 0
                ],
                'C' => [
                    'count' => $counts['C'] ?? 0,
                    'percentage' => $total > 0 ? round((($counts['C'] ?? 0) / $total) * 100) : 0
                ],
                'K' => [
                    'count' => $counts['K'] ?? 0,
                    'percentage' => $total > 0 ? round((($counts['K'] ?? 0) / $total) * 100) : 0
                ],
            ];
        }
        
        return $stats;
    }

    /**
     * Get average rating for trainer
     * Returns average poin (1-4 scale)
     */
    public static function getAverageRatingByTrainer($trainerId)
    {
        $evaluasi = self::where('trainer_id', $trainerId)->get();
        
        if ($evaluasi->isEmpty()) {
            return null;
        }
        
        $aspects = [
            'relevansi_materi',
            'pemahaman_materi',
            'penguasaan_trainer',
            'penyampaian_trainer',
            'fasilitas',
            'manfaat_keseluruhan'
        ];
        
        $totalPoin = 0;
        $count = 0;
        
        foreach ($evaluasi as $eval) {
            foreach ($aspects as $aspect) {
                if ($eval->$aspect) {
                    $totalPoin += self::getIndikatorPoin($eval->$aspect);
                    $count++;
                }
            }
        }
        
        return $count > 0 ? round($totalPoin / $count, 2) : 0;
    }

    /**
     * Get aspect labels for display
     */
    public static function getAspectLabels()
    {
        return [
            'relevansi_materi' => 'Relevansi Materi',
            'pemahaman_materi' => 'Pemahaman Materi',
            'penguasaan_trainer' => 'Penguasaan Trainer',
            'penyampaian_trainer' => 'Penyampaian Trainer',
            'fasilitas' => 'Fasilitas',
            'manfaat_keseluruhan' => 'Manfaat Keseluruhan'
        ];
    }

    /**
     * Check if user already evaluated this training
     */
    public static function hasUserEvaluated($trainingId, $pesertaId)
    {
        return self::where('training_id', $trainingId)
            ->where('peserta_id', $pesertaId)
            ->exists();
    }
}