<?php

namespace App\Http\Controllers\AppointmentHistories;

use App\Http\Controllers\Controller;
use App\Models\AppointmentHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $histories = AppointmentHistory::with([
            'appointment.doctor.user',
            'appointment.patient.user',
            'doctor.user',
            'patient.user'
        ])
        ->when($request->patient_id, function($query, $patientId) {
            return $query->where('patient_id', $patientId);
        })
        ->latest()
        ->get();

        return Inertia::render('AppointmentHistory/IndexAppointmentHistory', [
            'histories' => $histories,
            'patient' => $request->patient_id ? Patient::with('user')->find($request->patient_id) : null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AppointmentHistory $appointmentHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppointmentHistory $appointmentHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppointmentHistory $appointmentHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppointmentHistory $appointmentHistory)
    {
        //
    }

    public function downloadPdf(AppointmentHistory $history)
    {
        $history->load([
            'appointment.doctor.user',
            'appointment.patient.user',
            'doctor.user',
            'patient.user'
        ]);

        $pdf = Pdf::loadView('pdf.appointment-history', [
            'history' => $history,
        ]);

        return $pdf->download("cita-{$history->id}.pdf");
    }
}
