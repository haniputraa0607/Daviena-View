<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KodePandai\Indonesia\Models\District;
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
        'id_member',
        'is_suspended'
    ];

    public function partner_store()
    {
        return $this->hasOne(PartnerStore::class);
    }
}