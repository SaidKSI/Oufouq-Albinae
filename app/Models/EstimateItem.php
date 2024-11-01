<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_id',
        'ref',
        'name',
        'qte',
        'prix_unite',
        'category',
        'total_price_unite',
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }


}