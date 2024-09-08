<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['shift_id','employer_id', 'date', 'is_present', 'hours_worked'];

    public function employerShift()
    {
        return $this->belongsTo(EmployerShift::class);
    }
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }
}