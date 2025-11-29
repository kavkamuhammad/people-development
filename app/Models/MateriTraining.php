<?php
// app/Models/MateriTraining.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_materi',
        'nama_materi',
        'jenis_materi'
    ];
}