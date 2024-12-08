<?php

use App\Http\Controllers\AppointmentHistories\AppointmentHistoryController;
use App\Http\Controllers\Appointments\AppointmentController;
use App\Http\Controllers\Availabilities\AvailabilityController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Doctors\DoctorController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Users\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::prefix('usuario')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/registrar', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/eliminados', [UserController::class, 'deleted'])->name('users.deleted');
        Route::put('/restaurar/{user}', [UserController::class, 'restore'])->name('users.restore');
        Route::get('/editar/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });


    Route::prefix('doctor')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/registrar', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/store', [DoctorController::class, 'store'])->name('doctors.store');
        Route::get('/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
        Route::get('/editar/{doctor}', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    });

    Route::prefix('paciente')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/registrar', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/store', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('patients.show');
        Route::get('/editar/{patient}', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    });

    Route::prefix('cita')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/registrar', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::get('/editar/{appointment}', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
        Route::put('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    Route::prefix('disponibilidad')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index'])->name('availabilities.index');

        // Rutas estáticas primero
        Route::get('/registrar', [AvailabilityController::class, 'create'])->name('availabilities.create');
        Route::post('/store', [AvailabilityController::class, 'store'])->name('availabilities.store');

        // Rutas con parámetros después
        Route::get('/doctor/{doctor}', [AvailabilityController::class, 'show'])->name('availabilities.show');
        Route::get('/editar/{availability}', [AvailabilityController::class, 'edit'])->name('availabilities.edit');
        Route::put('/{availability}', [AvailabilityController::class, 'update'])->name('availabilities.update');
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    });

    Route::prefix('historial')->group(function () {
        Route::get('/', [AppointmentHistoryController::class, 'index'])->name('appointment-histories.index');
        Route::get('/{history}', [AppointmentHistoryController::class, 'show'])->name('appointment-histories.show');
        Route::get('/{history}/pdf', [AppointmentHistoryController::class, 'downloadPdf'])->name('appointment-histories.pdf');
    });


});
