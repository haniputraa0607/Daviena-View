<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;

// use Modules\Outlet\Entities\OutletSchedule;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'outlets';
    protected $fillable = [
        'name',
        'id_partner',
        'partner_equal_id',
        'outlet_code',
        // 'id_city',
        'outlet_phone',
        'outlet_email',
        // 'outlet_latitude',
        // 'outlet_longitude',
        'status',
        'is_tax',
        'address',
        'district_code',
        'postal_code',
        'coordinates',
        'google_maps_link',
        'activities',
        'images'
    ];

    protected $casts = [
        'district_code' => 'integer',
    ];


    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function outlet_schedule(): HasMany
    {
        return $this->hasMany(OutletSchedule::class)->orderBy('id');
    }

    public function partner_equal()
    {
        return $this->belongsTo(PartnerEqual::class, 'partner_equal_id');
    }
}
