<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PatientStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($user->role === 'doctor') {
            $this->failedAuthorization('No puedes registrarte como paciente porque ya estás registrado como doctor.');
        }
        return !$user->patient && $user->role !== 'doctor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|min:10|max:20',
            'address' => 'required|string|max:255',
        ];
    }
}
