<?php

namespace App\View\Components;

use App\Models\CompanySetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CompanyCapital extends Component
{
    public $capital;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->capital = CompanySetting::first()->capital;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.company-capital', ['capital' => $this->capital]);
    }
}