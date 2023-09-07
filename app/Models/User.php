<?php

namespace App\Models;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use KodePandai\Indonesia\Models\District;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    // use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'equal_id',
        'name',
        'username',
        'email',
        'phone',
        'idc',
        'birthdate',
        'email_verified_at',
        'type',
        'outlet_id',
        'admin_id',
        'password',
        'district_code',
        'address',
        'gender',
        'level',
        'consultation_price'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function admin(): HasOne
    {
        return $this->HasOne(Admin::class, 'id', 'id');
    }

    public function doctorShift(): HasOne
    {
        return $this->HasOne(DoctorShift::class);
    }

    public function shift(): BelongsToMany
    {
        return $this->belongsToMany(DoctorShift::class, 'user_has_shift', 'user_id', 'shift_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

//     public function employee_schedules(): HasMany
//     {
//         return $this->hasMany(EmployeeSchedule::class);
//     }

//     public function doctor_schedules(): HasMany
//     {
//         return $this->hasMany(DoctorSchedule::class);
//     }

    public function scopeDoctor(Builder $query): Builder
    {
        return $query->where('type', 'salesman');
    }

    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('type', 'admin');
    }

    public function scopeCashier(Builder $query): Builder
    {
        return $query->where('type', 'cashier');
    }

    public function scopeDisplay(Builder $query): Builder
    {
        return $query->with(['outlet'])
            ->select('id', 'name', 'idc', 'email', 'phone', 'birthdate', 'type', 'outlet_id');
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function get_features(): mixed
    {
        return $this->level == 'Super Admin' ? Feature::all()->pluck('id') : $this->admin->admin_features->map(fn ($item) => $item->feature_id);
    }

    public function findForPassport(string $username): User
    {
        return $this->where('phone', $username)->first();
    }
}
