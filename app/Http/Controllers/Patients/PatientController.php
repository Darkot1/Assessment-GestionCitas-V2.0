<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientDestroyRequest;
use App\Http\Requests\Patient\PatientStoreRequest;
use App\Http\Requests\Patient\PatientUpdateRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with('user')->get();
        return Inertia::render('Patient/IndexPatient', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->patient) {
            return redirect()->route('dashboard')->with('error', 'Ya estás registrado como paciente.');
        }
        return Inertia::render('Patient/CreatePatient');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientStoreRequest $request)
    {
        $request->validated();
        $user = Auth::user();
        Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        User::where('id', $user->id)->update(['role' => 'patient']);

        return redirect()->route('patients.index')
            ->with('success', 'Paciente registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load('user');
        return Inertia::render('Patient/ShowPatient', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $patient->user_id) {
            return redirect()->route('patients.index')
                ->with('error', 'No tienes permiso para editar este paciente');
        }

        $patient = Patient::with('user')->find($patient->id);

        return Inertia::render('Patient/EditPatient', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientUpdateRequest $request, Patient $patient)
    {
        $request->validated();
        $patient->update($request->all());

        return back()
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientDestroyRequest $request, Patient $patient)
    {
        $request->validated();
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Paciente desactivado exitosamente.');
    }
}
