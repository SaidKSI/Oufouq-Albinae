<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'ice',
        'status',
        'rating',
        'facebook_handle',
        'instagram_handle',
        'linkedin_handle',
        'twitter_handle',
    ];
   
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function delivery()
    {
        return $this->hasMany(Delivery::class);
    }

    public function getTotalDeliveryAmountAttribute()
    {
        return $this->delivery()->sum('total_with_tax');
    }

    public function getTotalPaidAmountAttribute()
    {
        return $this->delivery()->withSum('bills', 'amount')->get()->sum('bills_sum_amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_delivery_amount - $this->total_paid_amount;
    }

    public function getTotalRemainingAmountAttribute()
    {
        return $this->delivery()->sum('total_with_tax') - $this->delivery()->withSum('bills', 'amount')->get()->sum('bills_sum_amount');
    }
    
}