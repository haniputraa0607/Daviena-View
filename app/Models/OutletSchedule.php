<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutletSchedule extends Model
{
    use HasFactory;

    protected $table = 'outlet_schedules';
    protected $fillable = [
        'outlet_id',
        'day',
        'open',
        'close',
        'is_closed',
        'all_products',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function outlet_shifts(): HasMany
    {
        return $this->hasMany(OutletScheduleShift::class);
    }
}
