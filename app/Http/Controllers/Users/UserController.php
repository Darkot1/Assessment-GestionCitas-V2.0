<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
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
        $user = User::with(['doctor', 'patient'])->find($user->id);

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
        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyUserRequest $request, User $user)
    {
        $request->validated();

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario desactivado exitosamente.');
    }
}
