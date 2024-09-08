<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'type',
        'name',
        'amount',
        'description',
        'repeat',
        'start_date',
        'repeat_interval',
        'ref',
        'total_amount'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}