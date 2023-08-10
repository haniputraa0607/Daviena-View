<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;
use Modules\Outlet\Entities\OutletSchedule;

class PartnerSosialMedia extends Model
{
    use HasFactory;

    protected $table = 'partner_sosial_medias';
    protected $fillable = [
        'equal_id',
        'partner_store_id',
        'type',
        'url',
    ];

    protected static function newFactory()
    {
        return PartnerFactory::new();
    }
}
