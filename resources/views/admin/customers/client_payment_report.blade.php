@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Client Payment Report</h4>
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

                <form id="filterForm" action="{{ route('admin.client-payment.export.show') }}" name="exportShow" method="GET"
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
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" class="form-control" id="start_date"
                                    value="{{ request()->start_date }}">
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" class="form-control" id="end_date"
                                    value="{{ request()->end_date }}">
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Show" />
                                    <a class="btn btn-light mt-3 mt-lg-0"
                                        href="{{ URL::to('/admin/client-payment-report') }}">Clear</a>
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
                <button type="button" class="btn btn-success mb-3" id="exportBtn">Export Excel</button>
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
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
                        @foreach ($data as $customer)
                        <tr>
                            <td>{{ $customer->kid_name }}</td>
                            <td>{{ $customer->mobile }}</td>
                            <td>{{ !empty($customer->created_at) ? \Carbon\Carbon::parse($customer->created_at)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $customer->package_amount }}</td>
                            <td>{{ $customer->advanced }}</td>
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
</script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
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
                        $('#users_id').append('<option value="ALL">ALL</option>');

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