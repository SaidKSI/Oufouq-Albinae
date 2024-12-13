<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone1',
        'phone2',
        'fax',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'if',
        'ice',
        'rc',
        'cnss',
        'patente',
        'capital',
        'website',
        'logo',
        'footer_text',
        'bank_name',
        'bank_account',
        'bank_rib'
    ];

    protected $casts = [
        'capital' => 'decimal:2',
    ];
}