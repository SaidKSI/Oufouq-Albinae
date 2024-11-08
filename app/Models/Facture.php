<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_id',
        'number',
        'date',
        'payment_method',
        'transaction_id',
        'total_without_tax',
        'tax',
        'total_with_tax',
        'note'
    ];

    protected $casts = [
        'date' => 'date',
        'total_without_tax' => 'decimal:2',
        'tax' => 'decimal:10,2',
        'total_with_tax' => 'decimal:2',
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}