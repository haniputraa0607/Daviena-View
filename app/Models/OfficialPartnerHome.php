<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficialPartnerHome extends Model
{
    use HasFactory;

    protected $table = 'official_partner_home';
    protected $fillable = [
        'partner_equal_id',
    ];
}
