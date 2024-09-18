<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployerPaid
{
    use Dispatchable, SerializesModels;

    public $amount;

    /**
     * Create a new event instance.
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }
}