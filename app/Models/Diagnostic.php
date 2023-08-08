<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $table = 'diagnostics';
    protected $fillable = [
        'diagnostic_name',
        'description',
        'is_active'
    ];

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
