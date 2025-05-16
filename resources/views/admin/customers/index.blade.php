@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Lists</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div id="right">
                    <div id="menu" class="mb-3">
                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">

                                <a class="btn btn-info waves-effect waves-light"
                                    href="{{ route('admin.customers.create') }}"><i class="fa fa-plus editable"
                                        style="font-size:15px;">&nbsp;ADD</i></a>
                                @if (Session::get('user')['role'] == 'Admin')
                                <button class="btn btn-danger waves-effect waves-light" id="delete_selected_btn"
                                    style="font-size:15px;">Delete Selected</button>
                                @endif

                                <form method="GET" action="{{ route('admin.customers.index') }}"
                                    class="d-flex align-items-center gap-2 flex-wrap mb-0">
                                    <select name="date_filter" class="form-select form-select-sm w-auto"
                                        onchange="toggleCustomRange(this.value)">
                                        <option value="today"
                                            {{ request()->date_filter == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="yesterday"
                                            {{ request()->date_filter == 'yesterday' ? 'selected' : '' }}>Yesterday
                                        </option>
                                        <option value="week" {{ request()->date_filter == 'week' ? 'selected' : '' }}>
                                            This Week</option>
                                        <option value="last_week"
                                            {{ request()->date_filter == 'last_week' ? 'selected' : '' }}>Last Week
                                        </option>
                                        <option value="month"
                                            {{ request()->date_filter == 'month' ? 'selected' : '' }}>This Month
                                        </option>
                                        <option value="last_month"
                                            {{ request()->date_filter == 'last_month' ? 'selected' : '' }}>Last Month
                                        </option>
                                        <option value="custom"
                                            {{ request()->date_filter == 'custom' ? 'selected' : '' }}>Custom Range
                                        </option>
                                        <option value="all" {{ request()->date_filter == 'all' ? 'selected' : '' }}>
                                            All</option>
                                    </select>

                                    <input type="date" name="start_date" class="form-control form-control-sm w-auto"
                                        value="{{ request('start_date') }}" id="start_date"
                                        style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">

                                    <input type="date" name="end_date" class="form-control form-control-sm w-auto"
                                        value="{{ request('end_date') }}" id="end_date"
                                        style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">

                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Filter</button>
                                </form>

                            </div>
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            @if (Session::get('user')['role'] == 'Admin')
                            <th><input type="checkbox" id="select_all"></th>
                            @endif
                            <th>Link</th>
                            <th>Branch</th>
                            <th>User</th>
                            <th>Kid Name</th>
                            <th>Father Name</th>
                            <th>Mother Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Whatsapp No</th>
                            <th>Package</th>
                            <th>Package Amount</th>
                            <th>Advanced</th>
                            <th>Balance</th>
                            <th>Verified</th>
                            <th>Verified At</th>
                            <th>Address</th>
                            <th>Remark</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.customers.destroy', $customer->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                                <a href="#" onclick="openPaymentModal({{ $customer->id }})"
                                    class="btn btn-outline-info waves-effect waves-light">
                                    <!-- <i class="fa fa-list"></i> -->Pay
                                </a>
                            </td>
                            @if (Session::get('user')['role'] == 'Admin')
                            <td><input type="checkbox" class="row_checkbox" value="{{ $customer->id }}"></td>
                            @endif
                            <td align="center">
                                <a href="{{ URL::to('show/' . $customer->link) }}" target="_blank"><u>Link</u></a>
                                <br>
                                <button class="btn btn-sm btn-outline-secondary ms-2 copy-btn"
                                    data-link="{{ URL::to('show/' . $customer->link) }}">
                                    Copy
                                </button>
                            </td>
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
                            <td>{{ $customer->verified_at }}</td>
                            <td>
                                <p class="add-read-more show-less-content">{{ $customer->address }}</p>
                            </td>
                            <td>
                                <p class="add-read-more show-less-content">{{ $customer->remark }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p><strong class="text-danger">Pending Amount:</strong> <span id="currentBalance">0.00</span></p>
                <!-- Payment Form -->
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="customers_id" id="customer_id">
                    <input type="hidden" name="id" id="payment_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date<span
                                        class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control"
                                    placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button class="btn btn-secondary" onclick="clearAll()">Clear</button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Payments Table -->
                <table class="table table-bordered mt-3" id="paymentsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function toggleCustomRange(value) {
        const show = value === 'custom';
        document.getElementById('start_date').style.display = show ? 'block' : 'none';
        document.getElementById('end_date').style.display = show ? 'block' : 'none';
    }
</script>
<script>
    // Select/Deselect All
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.row_checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Uncheck Select All if any individual checkbox is unchecked
    document.querySelectorAll('.row_checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('select_all').checked = false;
            } else {
                let allChecked = [...document.querySelectorAll('.row_checkbox')].every(cb => cb
                    .checked);
                document.getElementById('select_all').checked = allChecked;
            }
        });
    });

    // Delete selected
    document.getElementById('delete_selected_btn').addEventListener('click', function() {
        let selectedIds = Array.from(document.querySelectorAll('.row_checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert("Please select at least one Lead.");
            return;
        }

        if (!confirm("Are you sure you want to delete selected Leads?")) return;

        fetch("{{ route('admin.customers.bulkDelete') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert("Something went wrong.");
                }
            })
            .catch(err => console.error(err));
    });
</script>
<!-- <script>
    function openPaymentModal(customerId) {
        $('#customer_id').val(customerId);
        $('#payment_id').val('');
        $('#date').val('');
        $('#amount').val('');
        fetchPayments(customerId);
        $('#paymentModal').modal('show');
    }

    function fetchPayments(customerId) {
        $.get(`/payments/${customerId}`, function(response) {
            let rows = '';
            response.payments.forEach((payment, index) => {
                rows += `<tr>
                <td>${payment.date}</td>
                <td>${payment.amount}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editPayment(${payment.id})">Edit</button>
                    ${
                        index > 0 
                        ? `<button class="btn btn-sm btn-danger" onclick="deletePayment(${payment.id})">Delete</button>` 
                        : ''
                    }
                </td>
            </tr>`;
            });
            const balance = parseFloat(response.balance) || 0;
            $('#currentBalance').text(balance.toFixed(2));
            $('#paymentsTable tbody').html(rows);
        });
    }

    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.post(`/payments/save`, formData, function(response) {
            $('#payment_id').val('');
            $('#date').val('');
            $('#amount').val('');
            fetchPayments($('#customer_id').val());
        });
    });

    function clearAll() {
        $('#customer_id').val('');
        $('#payment_id').val('');
        $('#date').val('');
        $('#amount').val('');
    }

    function editPayment(id) {
        $.get(`/payments/edit/${id}`, function(payment) {
            $('#payment_id').val(payment.id);
            $('#date').val(payment.date);
            $('#amount').val(payment.amount);
        });
    }

    function deletePayment(id) {
        if (confirm('Are you sure to delete this payment?')) {
            $.ajax({
                url: `/payments/delete/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    fetchPayments($('#customer_id').val());
                }
            });
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const link = this.getAttribute('data-link');
                navigator.clipboard.writeText(link).then(() => {
                    this.textContent = 'Copied!';
                    setTimeout(() => this.textContent = 'Copy', 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });
        });
    });
</script> -->
@endsection