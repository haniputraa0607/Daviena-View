<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactSosialMedia extends Model
{
    use HasFactory;

    protected $table = 'contact_sosial_medias';
    protected $fillable = [
        'type',
        'username',
        'link',
    ];
}
