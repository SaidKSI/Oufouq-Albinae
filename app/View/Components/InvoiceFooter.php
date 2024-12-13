<?php

namespace App\View\Components;

use App\Models\CompanySetting;
use Illuminate\View\Component;

class InvoiceFooter extends Component
{
  public $settings;

  public function __construct()
  {
    $this->settings = CompanySetting::first();
  }

  public function render()
  {
    return view('components.invoice-footer');
  }
}