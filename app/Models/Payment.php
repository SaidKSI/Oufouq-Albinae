<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'employee_id',
        'paid_price',
        'payment_id',
        'remaining',
        'payment_method',
        'date',
        'type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employer::class);
    }
    public function documents()
    {
        return $this->morphMany('App\Models\Document', 'documentable');
    }
}