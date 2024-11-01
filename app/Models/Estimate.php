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
        'due_date',
        'quantity',
        'tax',
        'payment_method',
        'transaction_id',
        'note',
    ];


    public function items()
    {
        return $this->hasMany(EstimateItem::class);
    }

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
        return $this->morphMany(Document::class, 'documentable');
    }
}