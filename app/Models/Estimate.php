<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'number',
        'type',
        'total_price',
        'reference',
        'due_date',
        'quantity',
        'tax',
        'payment_method',
        'transaction_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeEstimate($query)
    {
        return $query->where('type', 'estimate');
    }

    public function scopeInvoice($query)
    {
        return $query->where('type', 'invoice');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}