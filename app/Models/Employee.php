<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'department_id',
        'job_level_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class);
    }

    public function trainingPeserta()
    {
        return $this->hasMany(TrainingPeserta::class);
    }

    public function evaluasiTrainers()
    {
        return $this->hasMany(EvaluasiTrainer::class);
    }

    public function trainingRecords()
{
    return $this->hasMany(TrainingPeserta::class, 'employee_id');
}

public function evaluasiAtasanReceived()
{
    return $this->hasManyThrough(
        EvaluasiAtasan::class,
        TrainingPeserta::class,
        'employee_id', // Foreign key on TrainingPeserta
        'peserta_id',  // Foreign key on EvaluasiAtasan
        'id',          // Local key on Employee
        'id'           // Local key on TrainingPeserta
    );
}
}