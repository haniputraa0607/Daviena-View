<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_type',
        'feature_module',
        'show_hide',
        'order'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
