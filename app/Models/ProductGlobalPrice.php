<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGlobalPrice extends Model
{
    use HasFactory;

    protected $table = 'product_global_prices';
    protected $fillable = [
        'product_id',
        'price',
    ];
}
