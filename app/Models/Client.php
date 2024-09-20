<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'address',
        'ice',
        'city',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}