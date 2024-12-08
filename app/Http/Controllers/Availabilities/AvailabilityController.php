<?php

namespace App\Http\Controllers\Availabilities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AvailabilityController extends Controller
{

    public function index()
    {
        $query = Availability::with(['doctor.user'])
            ->orderBy('start_date')
            ->orderBy('start_time');

        if (Auth::user()->role === 'doctor') {
            $query->where('doctor_id', Auth::user()->doctor->id);
        }

        return Inertia::render('Availability/IndexAvailability', [
            'availabilities' => $query->get(),
            'isAdmin' => Auth::user()->role === 'admin'
        ]);
    }

    public function create()
    {
        if (Auth::user()->role === 'admin'|| Auth::user()->role === 'doctor') {
            return Inertia::render('Availability/CreateAvailability');
        }

        return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
    }

    public function store(StoreAvailabilityRequest $request)
    {
        $request->validated();


    $startTime = Carbon::parse($request->start_time);
    $endTime = Carbon::parse($request->end_time);

    $availabilitySlots = [];
    while ($startTime < $endTime) {
        $availabilitySlots[] = $startTime->format('H:i');
        $startTime->addMinutes(45);
    }

    // Guardar la disponibilidad
    foreach ($availabilitySlots as $availabilitySlot) {
        Availability::create([
            'doctor_id' => Auth::user()->doctor->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $availabilitySlot,
            'end_time' => Carbon::parse($availabilitySlot)->addMinutes(45)->format('H:i'),
        ]);

    }
    return redirect()->route('availabilities.index')
        ->with('success', 'Disponibilidad creada exitosamente.');
    }

    public function show(Doctor $doctor)
    {
        $availabilities = Availability::where('doctor_id', $doctor->id)
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get();

        $doctor->load('user');
        return Inertia::render('Availability/ShowAvailability',
         compact('doctor', 'availabilities'));
    }

    public function edit(Availability $availability)
    {
        $availability->load('doctor.user');

        if (Auth::user()->role !== 'admin' &&
            Auth::user()->doctor->id !== $availability->doctor_id) {
            return redirect()
                ->route('availabilities.index')
                ->with('error', 'No tienes permiso para editar esta disponibilidad');
        }

        return Inertia::render('Availability/EditAvailability', compact('availability'));
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability)
    {
        if (Auth::user()->role !== 'admin' &&
            Auth::user()->doctor->id !== $availability->doctor_id) {
            return redirect()
                ->route('availabilities.index')
                ->with('error', 'No tienes permiso para editar esta disponibilidad');
        }

        $validated = $request->validated();

        DB::transaction(function() use ($validated, $availability) {
            $availability->update($validated);

            if (in_array($validated['status'], ['completed', 'cancelled'])) {
                $availability->delete();
            }
        });

        return redirect()
            ->route('availabilities.index')
            ->with('success', 'Disponibilidad actualizada correctamente');
    }

    public function destroy(Availability $availability)
    {
        if (Auth::user()->role !== 'admin' &&
            Auth::user()->doctor->id !== $availability->doctor_id) {
            return back()->with('error', 'No tienes permiso para eliminar esta disponibilidad');
        }

        $availability->delete();

        return back()->with('success', 'Disponibilidad eliminada correctamente');
    }
}
