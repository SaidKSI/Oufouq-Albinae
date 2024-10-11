<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBills extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'bill_number',
        'bill_date',
        'amount',
        'payment_method',
        'note',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}