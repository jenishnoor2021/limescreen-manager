<table class="table table-bordered">
    <thead>
        <tr>
            <th><strong>Kid's Name</strong></th>
            <th><strong>Mobile</strong></th>
            <th><strong>Import Date</strong></th>
            <th><strong>Package</strong></th>
            <th><strong>Advace</strong></th>
            <th><strong>Balcnce</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $customer)
        <tr>
            <td>{{ $customer->kid_name }}</td>
            <td>{{ $customer->mobile }}</td>
            <td>{{ !empty($customer->created_at) ? \Carbon\Carbon::parse($customer->created_at)->format('d-m-Y') : '-' }}</td>
            <td>{{ $customer->package_amount }}</td>
            <td>{{ $customer->advanced }}</td>
            <td>{{ $customer->balance }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6">
                <center>No data found.</center>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>