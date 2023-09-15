<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductCategory;
use App\Models\ProductGlobalPrice;
use App\Models\ProductOutletPrice;
use App\Models\ProductOutletStock;
use App\Models\TreatmentOutlet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\TreatmentPatient;

class ProductTrending extends Model
{
    use HasFactory;

    protected $table = 'product_trendings';
    protected $fillable = [
        'product_id',
    ];
}
