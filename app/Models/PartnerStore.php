<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;
use App\Models\PartnerSosialMedia;

class PartnerStore extends Model
{
    use HasFactory;

    protected $table = 'partner_stores';
    protected $fillable = [
        'equal_id',
        'partner_equal_id',
        'store_name',
        'store_address',
        'store_city'
    ];

    protected static function newFactory()
    {
        return PartnerFactory::new();
    }

    public function partner_sosial_media(): HasMany
    {
        return $this->hasMany(PartnerSosialMedia::class);
    }
}
