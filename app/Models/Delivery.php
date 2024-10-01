<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'date',
        'client_id',
        'project_id',
        'supplier_id',
        'total_without_tax',
        'tax',
        'total_with_tax',
        'doc',
        'note',
        'payment_method'
    ];

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}