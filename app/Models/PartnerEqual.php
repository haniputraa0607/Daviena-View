<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\City;
use Modules\Outlet\Entities\OutletSchedule;
use App\Models\PartnerStore;

class PartnerEqual extends Model
{
    use HasFactory;

    protected $table = 'partner_equals';
    protected $fillable = [
        'equal_id',
        'name',
        'email',
        'phone',
        'type',
        'images',
        'city_code',
        'id_member',
        'is_suspended'
    ];

    public function partner_store()
    {
        return $this->hasOne(PartnerStore::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }
}
