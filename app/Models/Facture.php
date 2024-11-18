<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_id',
        'delivery_id',
        'number',
        'date',
        'payment_method',
        'transaction_id',
        'total_without_tax',
        'tax',
        'total_with_tax',
        'note',
        'doc'
    ];

    protected $casts = [
        'date' => 'date',
        'total_without_tax' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_with_tax' => 'decimal:2',
    ];

    // Format numbers before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($facture) {
            $facture->total_without_tax = number_format((float)$facture->total_without_tax, 2, '.', '');
            $facture->tax = number_format((float)$facture->tax, 2, '.', '');
            $facture->total_with_tax = number_format((float)$facture->total_with_tax, 2, '.', '');
        });
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}