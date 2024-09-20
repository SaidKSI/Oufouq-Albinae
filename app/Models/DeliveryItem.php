<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'name',
        'qte',
        'prix_unite',
        'category',
        'total_price_unite',
    ];
}