<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Requests\Doctor\DestroyDoctorRequest;
use App\Models\Availability;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return Inertia::render('Doctor/IndexDoctor', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar si el usuario ya está registrado como doctor
        if (Auth::user()->doctor) {
            return redirect()->route('dashboard')->with('error', 'Ya estás registrado como doctor.');
        }

        return Inertia::render('Doctor/CreateDoctor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        $request->validated();
        $user = Auth::user();

        // Crear doctor
        Doctor::create([
            'user_id' => $user->id,
            'specialty' => $request->specialty,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        User::where('id', $user->id)->update(['role' => 'doctor']);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor = Doctor::with('user')->find($doctor->id);

        return Inertia::render('Doctor/ShowDoctor', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        // Verificar si es admin o el propio doctor
        if (Auth::user()->role !== 'admin' && Auth::id() !== $doctor->user_id) {
            return redirect()->route('doctors.index')
                ->with('error', 'No tienes permiso para editar este doctor');
        }

        // Cargar las relaciones necesarias
        $doctor = Doctor::with('user')->find($doctor->id);

        // Mostrar formulario de edición
        return Inertia::render('Doctor/EditDoctor', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyDoctorRequest $request, Doctor $doctor)
    {
        $request->validated();

        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor desactivado exitosamente.');
    }
}
