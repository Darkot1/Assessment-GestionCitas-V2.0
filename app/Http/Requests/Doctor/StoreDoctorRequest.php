<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDoctorRequest extends FormRequest
{
    public function authorize()
    {
        $user = Auth::user();
        if ($user->role === 'patient') {
            $this->failedAuthorization('No puedes registrarte como doctor porque ya estás registrado como paciente.');
        }
        return !$user->doctor && $user->role !== 'patient';
    }

    public function rules()
    {
        return [
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:20',
            'address' => 'required|string|max:255',
        ];
    }
}
