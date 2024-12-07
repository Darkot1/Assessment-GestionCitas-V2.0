<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'specialty',
        'availability',
        'phone',
        'address',
    ];

    protected $casts = [
        'availability' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
