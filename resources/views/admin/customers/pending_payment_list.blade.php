@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Pending Payment List</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form id="filterForm" action="{{ route('admin.pending-payment.list.show') }}" name="exportShow" method="GET"
                    enctype="multipart/form-data">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-3">
                                <label for="branches_id">Branch</label>
                                <select name="branches_id" id="branches_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request()->branches_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('branches_id'))
                                <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-3">
                                <label for="users_id">Users</label>
                                <select name="users_id" id="users_id" class="form-select" required>
                                    <option value="">Select User</option>
                                </select>
                                @if ($errors->has('users_id'))
                                <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="date_filter">Date</label>
                                <select name="date_filter" class="form-select w-auto"
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
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="start_date" id="start_label">Start Date</label>
                                <input type="date" name="start_date" class="form-control w-auto"
                                    value="{{ request()->start_date }}" id="start_date"
                                    style="display: {{ request()->date_filter == 'custom' ? 'block' : 'none' }};">
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date" id="end_label">End Date</label>
                                <input type="date" name="end_date" class="form-control w-auto"
                                    value="{{ request()->end_date }}" id="end_date"
                                    style="display: {{ request()->date_filter == 'custom' ? 'block' : 'none' }};">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Show" />
                                    <a class="btn btn-light mt-3 mt-lg-0"
                                        href="{{ URL::to('/admin/pending-payment-list') }}">Clear</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (count($data) > 0)
        <div class="card">
            <div class="card-body">
                @if (Session::get('user')['role'] == 'Admin')
                <button type="button" class="btn btn-success mb-3" id="exportBtn">Export Excel</button>
                @endif
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th><strong>Kid's Name</strong></th>
                            <th><strong>Mobile</strong></th>
                            <th><strong>Due Date</strong></th>
                            <!-- <th><strong>Import Date</strong></th> -->
                            <!-- <th><strong>Package</strong></th> -->
                            <!-- <th><strong>Advace</strong></th> -->
                            <th><strong>Balance</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $customer)
                        <tr>
                            <td>
                                <i class="fa fa-eye text-primary" style="cursor: pointer; font-size:15px;"
                                    data-id="{{ $customer->id }}"
                                    onclick="showCustomerModal(this)">
                                </i>
                            </td>
                            <td>{{ $customer->kid_name }}</td>
                            <td>{{ $customer->mobile }}</td>
                            <td>{{ !empty($customer->due_date) ? \Carbon\Carbon::parse($customer->due_date)->format('d-m-Y') : '-' }}</td>
                            <!-- <td>{{ !empty($customer->created_at) ? \Carbon\Carbon::parse($customer->created_at)->format('d-m-Y') : '-' }}</td> -->
                            <!-- <td>{{ $customer->package_amount }}</td>
                            <td>{{ $customer->advanced }}</td> -->
                            <td>{{ $customer->balance }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @elseif(request()->branches_id != '')
        <div class="card">
            <div class="card-body">
                <span class="text-danger">No Record found</span>
            </div>
        </div>
        @endif

    </div>
</div>
<!-- end row -->

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(function() {
        $("form[name='exportShow']").validate({
            rules: {
                branches_id: {
                    required: true,
                },
                users_id: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    let slDate = document.getElementById('start_label');
    let elDate = document.getElementById('end_label');
    slDate.style.display = 'none';
    elDate.style.display = 'none';

    function toggleCustomRange(value) {
        const isCustom = value === 'custom';
        let sDate = document.getElementById('start_date');
        let eDate = document.getElementById('end_date');

        sDate.style.display = isCustom ? 'block' : 'none';
        eDate.style.display = isCustom ? 'block' : 'none';
        slDate.style.display = isCustom ? 'block' : 'none';
        elDate.style.display = isCustom ? 'block' : 'none';


        if (!isCustom) {
            sDate.value = '';
            eDate.value = '';
            slDate.style.display = 'none';
            elDate.style.display = 'none';
        }
    }

    function showCustomerModal(el) {
        const customerId = $(el).data('id');

        // Optional: show loader
        $('#modalBodyContent').html('<p class="text-center">Loading...</p>');

        // Fetch data via AJAX
        $.ajax({
            url: `/admin/customer-detail/${customerId}`, // Adjust route
            type: 'GET',
            success: function(response) {
                $('#modalBodyContent').html(response);
                $('#infoModal').modal('show');
            },
            error: function() {
                $('#modalBodyContent').html('<p class="text-danger">Failed to load data.</p>');
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        function loadUsers(branchId, selectedUserId = '') {
            if (branchId) {
                $.ajax({
                    url: '/get-users-by-branch/' + branchId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#users_id').empty();

                        if (loginRole !== 'Manager') {
                            $('#users_id').append('<option value="ALL">ALL</option>');
                        }

                        $.each(data, function(key, user) {
                            let selected = user.id == selectedUserId ? 'selected' : '';
                            $('#users_id').append('<option value="' + user.id + '" ' +
                                selected + '>' + user.name + '</option>');
                        });

                        if (selectedUserId === 'ALL') {
                            $('#users_id').val('ALL');
                        }
                    }
                });
            } else {
                $('#users_id').empty().append('<option value="">Select User</option>');
            }
        }

        // On change
        $('#branches_id').change(function() {
            loadUsers($(this).val());
        });

        // On page load
        const initialBranchId = '{{ request()->branches_id }}';
        const initialUserId = '{{ request()->users_id }}';

        if (initialBranchId) {
            loadUsers(initialBranchId, initialUserId);
        }
    });

    $('#exportBtn').on('click', function() {
        // $('#filterForm').data('exporting', true).trigger('submit');
        const form = $('#filterForm');
        const action = form.attr('action');
        const formData = form.serialize(); // convert form data to query string

        // Create a hidden iframe or redirect
        const url = `${action}?${formData}&download=1`;
        window.location.href = url;
    });
</script>
@endsection