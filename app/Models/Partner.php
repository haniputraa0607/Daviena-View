<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;
use Modules\Outlet\Entities\OutletSchedule;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';
    protected $fillable = [
        'partner_code',
        'partner_name',
        'partner_email',
        'partner_phone',
        'partner_location',
        'partner_account_instagram',
        'partner_account_shoope',
        'partner_address'
    ];

    protected static function newFactory()
    {
        return PartnerFactory::new();
    }
}
