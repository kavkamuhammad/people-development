<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobLevelController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MateriTrainingController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\EvaluasiTrainerController;
use App\Http\Controllers\EvaluasiAtasanController;
use App\Http\Controllers\ObservasiTrainingController;
use App\Http\Controllers\TrainingRecordController;
use App\Http\Controllers\DashboardController; // ✅ TAMBAHKAN INI

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index']) // ✅ UBAH INI
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // API untuk Select2 & Employee Data
        Route::get('/api/search-employees', [UserController::class, 'searchEmployees'])->name('search-employees');
        Route::get('/api/employee-data/{employeeId}', [UserController::class, 'getEmployeeData'])->name('employee-data');
    });

    /*
    |--------------------------------------------------------------------------
    | Role Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::patch('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Data Master
    |--------------------------------------------------------------------------
    */
    Route::prefix('data-master')->name('data-master.')->group(function () {

        // Employees
        Route::resource('employees', EmployeeController::class)->except(['show']);

        // Departments
        Route::resource('departments', DepartmentController::class)->except(['show']);

        // Job Levels
        Route::resource('job-levels', JobLevelController::class)
            ->except(['show'])
            ->parameters([
                'job-levels' => 'jobLevel'
            ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Trainer & Materi Training
    |--------------------------------------------------------------------------
    */
    Route::resource('trainers', TrainerController::class)->except(['show']);
    Route::resource('materi-trainings', MateriTrainingController::class)->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Training Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('training')->name('training.')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('index');
        Route::get('/create', [TrainingController::class, 'create'])->name('create');
        Route::post('/', [TrainingController::class, 'store'])->name('store');
        Route::get('/{training}', [TrainingController::class, 'show'])->name('show');
        Route::get('/{training}/edit', [TrainingController::class, 'edit'])->name('edit');
        Route::put('/{training}', [TrainingController::class, 'update'])->name('update');
        Route::delete('/{training}', [TrainingController::class, 'destroy'])->name('destroy');

         Route::get('/{training}/peserta', [TrainingController::class, 'pesertaDetail'])->name('peserta.detail');
        Route::post('/{training}/peserta/{peserta}/update-scores', [TrainingController::class, 'updateScores'])->name('peserta.update-scores');
    });

    /*
    |--------------------------------------------------------------------------
    | Evaluasi Trainer
    |--------------------------------------------------------------------------
    */
  Route::prefix('evaluasi-trainer')->name('evaluasi-trainer.')->group(function () {
    Route::get('/', [EvaluasiTrainerController::class, 'index'])->name('index');
    Route::get('/create/{training}', [EvaluasiTrainerController::class, 'create'])->name('create');
    Route::post('/store/{training}', [EvaluasiTrainerController::class, 'store'])->name('store');
    Route::get('/show/{training}', [EvaluasiTrainerController::class, 'show'])->name('show');
    
    // CRUD untuk evaluasi individual
    Route::get('/edit/{evaluasi}', [EvaluasiTrainerController::class, 'edit'])->name('edit');
    Route::put('/update/{evaluasi}', [EvaluasiTrainerController::class, 'update'])->name('update');
    Route::delete('/destroy/{evaluasi}', [EvaluasiTrainerController::class, 'destroy'])->name('destroy');
    
    // Delete all evaluations for a training
    Route::delete('/destroy-all/{training}', [EvaluasiTrainerController::class, 'destroyAll'])->name('destroyAll');
});

    /*
    |--------------------------------------------------------------------------
    | Evaluasi Atasan Langsung
    |--------------------------------------------------------------------------
    */
    Route::prefix('evaluasi-atasan')->name('evaluasi-atasan.')->group(function () {
        Route::get('/', [EvaluasiAtasanController::class, 'index'])->name('index');
        Route::get('/{training}/create', [EvaluasiAtasanController::class, 'create'])->name('create');
        Route::post('/{training}', [EvaluasiAtasanController::class, 'store'])->name('store');
        Route::get('/{training}/show', [EvaluasiAtasanController::class, 'show'])->name('show');
        Route::get('/{evaluasiAtasan}/edit', [EvaluasiAtasanController::class, 'edit'])->name('edit');
        Route::put('/{evaluasiAtasan}', [EvaluasiAtasanController::class, 'update'])->name('update');
        Route::get('/{evaluasiAtasan}/print', [EvaluasiAtasanController::class, 'print'])->name('print');
    });

    /*
    |--------------------------------------------------------------------------
    | Observasi Training
    |--------------------------------------------------------------------------
    */
    Route::prefix('observasi-training')->name('observasi-training.')->group(function () {
        Route::get('/', [ObservasiTrainingController::class, 'index'])->name('index');
        Route::get('/create/{training}', [ObservasiTrainingController::class, 'create'])->name('create');
        Route::post('/store/{training}', [ObservasiTrainingController::class, 'store'])->name('store');
        Route::get('/show/{training}', [ObservasiTrainingController::class, 'show'])->name('show');
        Route::get('/edit/{training}', [ObservasiTrainingController::class, 'edit'])->name('edit');
        Route::put('/update/{training}', [ObservasiTrainingController::class, 'update'])->name('update');
        Route::delete('/destroy/{training}', [ObservasiTrainingController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Training Record Karyawan
    |--------------------------------------------------------------------------
    */
    Route::prefix('training-record')->name('training-record.')->group(function () {
        Route::get('/', [TrainingRecordController::class, 'index'])->name('index');
        Route::get('/{employee}/show', [TrainingRecordController::class, 'show'])->name('show');
        Route::get('/{employee}/export', [TrainingRecordController::class, 'export'])->name('export');
        Route::post('/compare', [TrainingRecordController::class, 'compare'])->name('compare');
        Route::get('/report/department', [TrainingRecordController::class, 'reportByDepartment'])->name('report.department');
    });
});

require __DIR__.'/auth.php';
