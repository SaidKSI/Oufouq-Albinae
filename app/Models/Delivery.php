<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'estimate_id',
        'number',
        'date',
        'client_id',
        'project_id',
        'supplier_id',
        'total_without_tax',
        'tax',
        'tax_type',
        'total_with_tax',
        'doc',
        'note',
        'payment_method',
        'type'
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

    public function bills()
    {
        return $this->hasMany(DeliveryBills::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->bills()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_with_tax - $this->total_paid;
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public function hasFacture()
    {
        return !is_null($this->facture);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }
}