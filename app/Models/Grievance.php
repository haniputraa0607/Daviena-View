<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use HasFactory;

    protected $table = 'grievances';
    protected $fillable = [
        'grievance_name',
        'description',
        'is_active',
    ];
}
