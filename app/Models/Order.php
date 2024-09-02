<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'project_id',
        'Ref',
        'total_price',
        'paid_amount',
        'remaining',
        'status',
        'due_date',
        'description'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateTotalPrice()
    {
        $this->total_price = $this->items()->sum('total_price');
        $this->save();
    }

    // Method to add an item and update the total price
    public function addItem($itemData)
    {
        $item = $this->items()->create($itemData);
        $item->calculateTotalPrice();
        $this->updateTotalPrice();
    }
}