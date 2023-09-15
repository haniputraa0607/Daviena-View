<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\TreatmentPatient;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';
    protected $fillable = [
        'name',
        'email',
        'message'
    ];
}
