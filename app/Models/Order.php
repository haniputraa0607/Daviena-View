<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'patient_id',
        'outlet_id',
        'chasier_id',
        'order_date',
        'order_code',
        'notes',
        'order_subtotal',
        'order_gross',
        'order_discount',
        'order_tax',
        'order_grandtotal',
        'send_to_transaction',
        'is_submited',
        'is_submited_doctor',
        'status',
        'cancel_date',
        'parent_id',
    ];

    public function transactions()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id');
    }

    public function patient()
    {
        return $this->hasOne(Customer::class, 'id', 'patient_id');
    }

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'id', 'outlet_id');
    }

    public function chasier()
    {
        return $this->hasOne(User::class, 'id', 'chasier_id');
    }

    public function order_product()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
}
