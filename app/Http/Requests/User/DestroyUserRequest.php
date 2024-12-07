<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyUserRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->role === 'admin' ||
               Auth::user()->id === $this->user->id;
    }

    public function rules()
    {
        return [];
    }
}
