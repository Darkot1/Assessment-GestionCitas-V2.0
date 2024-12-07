<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->role === 'admin' ||
               Auth::user()->id === $this->user->id;
    }

    public function rules()
    {
        $rules = [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $this->user->id,
            'role' => 'in:patient,doctor,admin',
        ];
        
        return $rules;
    }
}
