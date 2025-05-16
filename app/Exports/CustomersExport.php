<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('partials.customer_report_modal', [
            'data' => $this->data
        ]);
    }
}
