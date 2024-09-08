<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'phone',
        'city',
        'cine',
        'address',
        'type',
        'wage',
        'profession_id',
        'cnss',
        'wage_per_hour'
    ];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function employerShifts()
    {
        return $this->hasMany(EmployerShift::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'employee_task');
    }
}