<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['doctor', 'patient'])->get();

        return Inertia::render('User/IndexUser', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/CreateUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
       $request->validated();

       User::create([
              'name' => $request->name,
              'email' => $request->email,
              'password' => Hash::make($request->password),
              'role' => $request->role,
       ]);

         return redirect()->route('users.index')
              ->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::with([
            'doctor.availabilities', 
            'patient'
        ])->find($user->id);

        return Inertia::render('User/ShowUser', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'No tienes permiso para editar este usuario');
        }

        $user = User::with(['doctor', 'patient'])->find($user->id);

        return Inertia::render('User/EditUser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $oldRole = $user->role;

        // Actualizar usuario base
        $user->update($validated);

        // Si cambia a doctor o ya es doctor
        if ($request->role === 'doctor') {
            // Actualizar o crear doctor
            $doctorData = [
                'specialty' => $request->specialty,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($user->doctor) {
                $user->doctor->update($doctorData);
            } else {
                Doctor::create([
                    'user_id' => $user->id,
                    ...$doctorData
                ]);
            }
        }

        // Si cambia a paciente o ya es paciente
        if ($request->role === 'patient') {
            $patientData = [
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($user->patient) {
                $user->patient->update($patientData);
            } else {
                Patient::create([
                    'user_id' => $user->id,
                    ...$patientData
                ]);
            }
        }


        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyUserRequest $request, User $user)
    {
        $request->validated();

        // Eliminar doctor
        if ($user->doctor) {
            $user->doctor->delete();
        }

        // Eliminar paciente
        if ($user->patient) {
            $user->patient->delete();
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario desactivado exitosamente.');
    }

    public function deleted()
    {
        $deletedUsers = User::onlyTrashed()->with(['doctor', 'patient'])->get();

        return Inertia::render('User/DeletedUsers', compact('deletedUsers')); // Cambiar a DeletedUsers
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        // Restaurar doctor
        Doctor::onlyTrashed()
            ->where('user_id', $id)
            ->restore();

        // Restaurar paciente
        Patient::onlyTrashed()
            ->where('user_id', $id)
            ->restore();

        $user->restore();

        return redirect()->route('users.deleted')
            ->with('success', 'Usuario restaurado exitosamente.');
    }
}
