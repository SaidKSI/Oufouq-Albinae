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
    
}