<?php

namespace App\Observers;

use App\Models\Availability;
use App\Models\Appointment;
use App\Models\AppointmentHistory;

class AvailabilityObserver
{
    public function updating(Availability $availability)
    {
        if ($availability->isDirty('status') && $availability->status === 'completed') {
            // Buscar la cita relacionada
            $appointment = Appointment::where('doctor_id', $availability->doctor_id)
                ->where('date', $availability->start_date)
                ->where('time', $availability->start_time)
                ->first();

            if ($appointment) {
                // Actualizar estado de la cita
                $appointment->update(['status' => 'completed']);

                // Crear registro en historial
                AppointmentHistory::create([
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'action' => 'completed',
                    'notes' => 'Cita completada'
                ]);
            }
        }
    }
}
