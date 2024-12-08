<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Availability;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        if (Auth::user()->role === 'patient') {
            // Verificar si el usuario tiene un perfil de paciente
            $patient = Auth::user()->patient;
            if (!$patient) {
                return redirect()->route('patients.create')
                    ->with('error', 'Necesitas completar tu perfil de paciente primero.');
            }
            $query->where('patient_id', $patient->id);
        } elseif (Auth::user()->role === 'doctor') {
            $query->where('doctor_id', Auth::user()->doctor->id);
        }

        $appointments = $query->latest()->get();

        return Inertia::render('Appointment/IndexAppointment', [
            'appointments' => $appointments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::with(['user', 'availabilities' => function($query) {
            $query->where('status', 'available')
                ->orderBy('start_date')
                ->orderBy('start_time');
        }])->get();

        return Inertia::render('Appointment/CreateAppointment', [
            'doctors' => $doctors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $availability = Availability::findOrFail($request->availability_id);

        DB::transaction(function() use ($request, $availability) {
            // Crear la cita
            $appointment = Appointment::create([
                'patient_id' => Auth::user()->patient->id,
                'doctor_id' => $request->doctor_id,
                'date' => $availability->start_date,
                'time' => $availability->start_time,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'status' => 'scheduled'
            ]);

            // Actualizar disponibilidad
            $availability->update([
                'status' => 'booked'
            ]);

            // Crear historial
            AppointmentHistory::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'action' => 'created',
                'notes' => 'Cita programada'
            ]);
        });

        return redirect()->route('appointments.index')
            ->with('success', 'Cita programada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }

    /**
     * Cancel the specified resource.
     */
    public function cancel(Appointment $appointment)
    {
        DB::transaction(function() use ($appointment) {
            // Actualizar el estado de la cita
            $appointment->update([
                'status' => 'cancelled'
            ]);

            // Crear historial
            AppointmentHistory::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'action' => 'cancelled',
                'notes' => 'Cita cancelada'
            ]);

            // Actualizar disponibilidad
            $availability = Availability::where('doctor_id', $appointment->doctor_id)
                ->where('start_date', $appointment->date)
                ->where('start_time', $appointment->time)
                ->first();

            if ($availability) {
                $availability->update([
                    'status' => 'available'
                ]);
            }
        });

        return redirect()->route('appointments.index')
            ->with('success', 'Cita cancelada correctamente');
    }
}
