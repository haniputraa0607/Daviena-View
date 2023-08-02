<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOutletStock extends Model
{
    use HasFactory;

    protected $table = 'product_outlet_stocks';
    protected $fillable = [
        'product_id',
        'outlet_id',
        'stock',
    ];
}
