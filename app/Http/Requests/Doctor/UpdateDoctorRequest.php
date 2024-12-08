<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize()
    {
        $doctor = $this->route('doctor');
        return Auth::user()->role === 'admin' ||
                Auth::id() === $doctor->user_id;
    }

    public function rules()
    {
        return [
            'specialty' => 'string|max:255',
            'phone' => 'string|min:10|max:20',
            'address' => 'string|max:255',
        ];
    }
}
