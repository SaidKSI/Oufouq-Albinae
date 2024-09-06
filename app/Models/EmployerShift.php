<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerShift extends Model
{
    use HasFactory;

    protected $fillable = ['employer_id', 'shift_id', 'is_present', 'hours_worked', 'total_wage'];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}