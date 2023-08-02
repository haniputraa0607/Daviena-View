<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentOutlet extends Model
{
    use HasFactory;

    protected $table = 'treatment_outlets';
    protected $fillable = [
        'treatment_id',
        'outlet_id',
        'is_active',
    ];
}
