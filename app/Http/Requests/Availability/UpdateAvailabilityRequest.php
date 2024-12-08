<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role === 'admin' ||
               Auth::user()->doctor->id === $this->availability->doctor_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:available,booked,unavailable,cancelled,completed',
            'reason' => 'nullable|string|max:255',
        ];
    }
}
