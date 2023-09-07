<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorScheduleDate extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedule_dates';
    protected $fillable = [
        'doctor_schedule_id',
        'date',
    ];

    public function doctorSchedule(): BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class);
    }
}
