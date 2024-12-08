<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
        'reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
