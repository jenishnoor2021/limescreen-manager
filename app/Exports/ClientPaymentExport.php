<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClientPaymentExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('partials.client_payment_report_modal', [
            'data' => $this->data
        ]);
    }
}
