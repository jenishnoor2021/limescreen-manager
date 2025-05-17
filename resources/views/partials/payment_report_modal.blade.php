<table class="table table-bordered">
    <thead>
        <tr>
            <th><strong>Kid's Name</strong></th>
            <th><strong>Mobile</strong></th>
            <th><strong>Date</strong></th>
            <th><strong>Amount</strong></th>
            <!-- <th><strong>Balance</strong></th> -->
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $payment)
        <tr>
            <td>{{ $payment->customers->kid_name }}</td>
            <td>{{ $payment->customers->mobile }}</td>
            <td>{{ !empty($payment->date) ? \Carbon\Carbon::parse($payment->date)->format('d-m-Y') : '-' }}</td>
            <td>{{ $payment->amount }}</td>
            <!-- <td></td> -->
        </tr>
        @empty
        <tr>
            <td colspan="5">
                <center>No data found.</center>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>