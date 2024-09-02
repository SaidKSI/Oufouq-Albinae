<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'wrong_attempts',
        'login_state',
        'ip_address',
        'user_agent',
        'is_online',
        'last_seen',
        'last_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //function to check if user is online depending if the last active time is less than 30 minutes
    // then update the user is_online to true
    public function isOnline()
    {
        $lastActive = $this->last_active;
        $now = now();
        $diff = $now->diffInMinutes($lastActive);
        if ($diff < 30) {
            $this->is_online = true;
            $this->save();
            return true;
        } else {
            $this->is_online = false;
            $this->save();
            return false;
        }
    }
}