<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date_begin', 'date_end'];

    public function employerShifts()
    {
        return $this->hasMany(EmployerShift::class);
    }
}