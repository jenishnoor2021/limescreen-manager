<table class="table table-bordered">
    <thead>
        <tr>
            <th><strong>Branch</strong></th>
            <th><strong>User</strong></th>
            <th><strong>Kid Name</strong></th>
            <th><strong>Father Name</strong></th>
            <th><strong>Mother Name</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Mobile</strong></th>
            <th><strong>Whatsapp No</strong></th>
            <th><strong>Package</strong></th>
            <th><strong>Package Amount</strong></th>
            <th><strong>Advanced</strong></th>
            <th><strong>Balance</strong></th>
            <th><strong>Verified</strong></th>
            <th><strong>Verified At</strong></th>
            <th><strong>Link</strong></th>
            <th><strong>Address</strong></th>
            <th><strong>Remark</strong></th>
            <th><strong>Created At</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $customer)
        <tr>
            <td>{{ $customer->branches->name }}</td>
            <td>{{ $customer->users->name }}</td>
            <td>{{ $customer->kid_name }}</td>
            <td>{{ $customer->father_name }}</td>
            <td>{{ $customer->mother_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->mobile }}</td>
            <td>{{ $customer->whatsapp_number }}</td>
            <td>{{ $customer->package }}</td>
            <td>{{ $customer->package_amount }}</td>
            <td>{{ $customer->advanced }}</td>
            <td>{{ $customer->balance }}</td>
            <td>{{ $customer->is_verified }}</td>
            <td>{{ !empty($customer->verified_at) ? \Carbon\Carbon::parse($customer->verified_at)->format('d-m-Y') : '-' }}</td>
            <td>{{ URL::to('show/' . $customer->link) }}</td>
            <td>{{ $customer->address }}</td>
            <td>{{ $customer->remark }}</td>
            <td>{{ $customer->created_at->format('d-m-Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="11">
                <center>No data found.</center>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>