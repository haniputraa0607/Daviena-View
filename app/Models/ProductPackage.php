<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\TreatmentPatient;

class ProductPackage extends Model
{
    use HasFactory;

    protected $table = 'product_packages';
    protected $fillable = [
        'package_id',
        'product_id'
    ];

    public function product(): HasMany
    {
        return $this->hasMany(ProductPackage::class, 'product_id', 'id');
    }
}
