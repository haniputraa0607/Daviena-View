<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactOfficial extends Model
{
    use HasFactory;

    protected $table = 'contact_official';
    protected $fillable = [
        'official_name',
        'official_value',
    ];
}
