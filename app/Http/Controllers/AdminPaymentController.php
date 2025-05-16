<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminPaymentController extends Controller
{
    public function getByCustomer($customerId)
    {
        $payments = Payment::where('customers_id', $customerId)->get();
        $customer = Customer::findOrFail($customerId);
        return response()->json([
            'payments' => $payments,
            'balance' => $customer->balance,
        ]);
    }

    public function edit($id)
    {
        return Payment::findOrFail($id);
    }

    public function save(Request $request)
    {
        $data = $request->only(['id', 'customers_id', 'date', 'amount']);
        $data['users_id'] = Session::get('user')->id;

        if ($request->id) {
            $payment = Payment::findOrFail($request->id);
            $payment->update($data);
        } else {
            Payment::create($data);
        }
        $customer = Customer::findOrFail($request->customers_id);
        $this->calculateBalance($customer->id, $customer->package_amount);

        return response()->json(['success' => true]);
    }

    function calculateBalance($id, $packAmt)
    {
        $total = Payment::where('customers_id', $id)->sum('amount');
        $balance = $packAmt - $total;

        $customer = Customer::findOrFail($id);
        $customer->update(['advanced' => $total, 'balance' => $balance]);

        return true;
    }

    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['success' => true]);
    }
}
