<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_project',
        'name',
        'description',
        'progress',
        'duration',
        'priority',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function employees()
    {
        return $this->belongsToMany(Employer::class, 'employee_task');
    }
}