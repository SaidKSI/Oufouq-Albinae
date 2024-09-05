<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationExpenses extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'product_id',
        'quantity',
        'highway_expense',
        'gaz_expense',
        'other_expense',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}