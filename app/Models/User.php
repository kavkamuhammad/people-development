<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'username',
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'job_level_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /* ============================
       RELATIONS
    ============================ */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class);
    }

    public function overtimeRequests()
    {
        return $this->hasMany(OvertimeRequest::class, 'requester_id');
    }

    public function overtimeDetails()
    {
        return $this->hasMany(OvertimeDetail::class, 'employee_id');
    }

    public function approvals()
    {
        return $this->hasMany(OvertimeApproval::class, 'approver_id');
    }

    public function evaluasiAtasanGiven()
    {
        return $this->hasMany(EvaluasiAtasan::class, 'atasan_id');
    }

    // ✔ FIX: Relasi Employee UTAMA pakai employee_id
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /* ============================
       HELPERS
    ============================ */

    public function isLevel($levelCode)
    {
        return $this->jobLevel && $this->jobLevel->code === $levelCode;
    }

    // ✔ Accessor tambahan untuk mengambil employee via employee_id atau email
    public function getEmployeeAttribute()
    {
        return Employee::where('employee_id', $this->employee_id)
            ->orWhere('email', $this->email)
            ->first();
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->permissions->contains('name', $permission);
    }
}
