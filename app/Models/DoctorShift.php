<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DoctorShift extends Model
{
    use HasFactory;

    protected $table = 'doctor_shifts';
    protected $fillable = [
        'user_id',
        'day',
        'name',
        'start',
        'end',
        'price'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_has_shift', 'shift_id', 'user_id');
    }
}
