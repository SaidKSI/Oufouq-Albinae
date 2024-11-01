<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'city',
        'address',
        'status',
        'progress_percentage'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function estimate()
    {
        return $this->hasOne(Estimate::class)->where('type', 'estimate');
    }

    public function invoices()
    {
        return $this->hasMany(Estimate::class)->where('type', 'invoice');
    }



    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($project) {
            if ($project->progress_percentage == 100) {
                $project->status = 'completed';
            }
        });
    }

    public function getTotalEstimateAmount()
    {
        return $this->estimate()->sum('total_price');
    }

    public function getTotalPaidAmount()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingAmount()
    {
        return $this->getTotalEstimateAmount() - $this->getTotalPaidAmount();
    }

    public function canAddPayment($amount)
    {
        return $amount <= $this->getRemainingAmount();
    }
}