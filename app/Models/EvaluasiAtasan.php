<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiAtasan extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_atasan';

    protected $fillable = [
        'training_id',
        'peserta_id',
        'atasan_id',
        'nama_karyawan',
        'materi_training',
        'departement',
        'tanggal_training',
        'peningkatan_keterampilan',
        'uraian_peningkatan_keterampilan',
        'penerapan_ilmu',
        'uraian_penerapan_ilmu',
        'perubahan_perilaku',
        'uraian_perubahan_perilaku',
        'dampak_performa',
        'uraian_dampak_performa',
        'total_skor',
        'kategori',
        'catatan_atasan',
    ];

    protected $casts = [
        'tanggal_training' => 'date',
    ];

    // ========== AUTO CALCULATE ==========
    
    /**
     * Boot method untuk auto-calculate saat save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateTotalAndKategori();
        });
    }

    // ========== RELATIONSHIPS ==========
    
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function peserta()
    {
        return $this->belongsTo(TrainingPeserta::class, 'peserta_id');
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    // ========== CALCULATION METHODS ==========
    
    /**
     * Calculate total score and category
     */
    public function calculateTotalAndKategori()
    {
        $total = 0;
        $total += $this->peningkatan_keterampilan ?? 0;
        $total += $this->penerapan_ilmu ?? 0;
        $total += $this->perubahan_perilaku ?? 0;
        $total += $this->dampak_performa ?? 0;

        $this->total_skor = $total;

        // Determine category based on total score
        if ($total >= 17) {
            $this->kategori = 'Sangat Baik';
        } elseif ($total >= 13) {
            $this->kategori = 'Baik';
        } elseif ($total >= 9) {
            $this->kategori = 'Cukup';
        } else {
            $this->kategori = 'Perlu Perbaikan';
        }
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Get kategori options with score range
     */
    public static function getKategoriOptions()
    {
        return [
            'Sangat Baik' => '17–20',
            'Baik' => '13–16',
            'Cukup' => '9–12',
            'Perlu Perbaikan' => '< 9'
        ];
    }

    /**
     * Get aspect labels
     */
    public static function getAspectLabels()
    {
        return [
            'peningkatan_keterampilan' => 'Peningkatan keterampilan setelah mengikuti training',
            'penerapan_ilmu' => 'Penerapan ilmu/pengetahuan training dalam pekerjaan sehari-hari',
            'perubahan_perilaku' => 'Perubahan perilaku kerja (inisiatif, disiplin, kerja sama, kepatuhan SOP)',
            'dampak_performa' => 'Dampak pada performa/hasil kerja (efisiensi, kualitas, produktivitas)'
        ];
    }

    /**
     * Get statistics by category
     */
    public static function getStatistikByKategori()
    {
        return self::selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->get()
            ->pluck('jumlah', 'kategori')
            ->toArray();
    }

    /**
     * Get average score by department
     */
    public static function getAverageByDepartment()
    {
        return self::selectRaw('departement, AVG(total_skor) as avg_skor, COUNT(*) as jumlah')
            ->groupBy('departement')
            ->orderByDesc('avg_skor')
            ->get();
    }

    /**
     * Get score label based on score value
     */
    public static function getScoreLabel($score)
    {
        $labels = [
            1 => 'Sangat Kurang',
            2 => 'Kurang',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik'
        ];

        return $labels[$score] ?? '-';
    }

    /**
     * Check if user already evaluated this participant
     */
    public static function hasAtasanEvaluated($trainingId, $pesertaId, $atasanId)
    {
        return self::where('training_id', $trainingId)
            ->where('peserta_id', $pesertaId)
            ->where('atasan_id', $atasanId)
            ->exists();
    }
}