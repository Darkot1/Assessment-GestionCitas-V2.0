<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'admin' || Auth::user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);
                    if ($date->isWeekend()) {
                        $fail('Solo se pueden seleccionar días de lunes a viernes.');
                    }
                }
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $date = Carbon::parse($value);
                        if ($date->isWeekend()) {
                            $fail('Solo se pueden seleccionar días de lunes a viernes.');
                        }
                    }
                }
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                'after_or_equal:08:00',
                'before_or_equal:17:00'
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                'before_or_equal:17:00'
            ],
            'status' => 'nullable|string|in:available,booked,unavailable',
            'reason' => 'nullable|string|max:255'

        ];
    }

    public function messages(): array
    {
        return [
            'start_time.after_or_equal' => 'El horario debe ser después de las 8:00 AM',
            'start_time.before_or_equal' => 'El horario debe ser antes de las 5:00 PM',
            'end_time.before_or_equal' => 'El horario debe ser antes de las 5:00 PM',
            'start_date.after_or_equal' => 'La fecha debe ser igual o posterior a hoy',
            'end_date.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->start_date) {
            $this->merge([
                'start_date' => Carbon::parse($this->start_date)->format('Y-m-d')
            ]);
        }
        if ($this->end_date) {
            $this->merge([
                'end_date' => Carbon::parse($this->end_date)->format('Y-m-d')
            ]);
        }
    }
}
