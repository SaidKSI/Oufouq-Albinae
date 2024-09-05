<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'payment_id',
        'paid_price',
        'remaining',
        'payment_method',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function documents()
    {
        return $this->morphMany('App\Models\Document', 'documentable');
    }
}