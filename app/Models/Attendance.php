<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['employer_shift_id', 'date', 'is_present', 'hours_worked'];

    public function employerShift()
    {
        return $this->belongsTo(EmployerShift::class);
    }
}