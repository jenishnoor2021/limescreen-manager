@extends('layouts.admin')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="row">

            @if (Session::get('user')['role'] == 'Admin')
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Branches</p>
                                <h4 class="mb-0">{{ $branchesCount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Users</p>
                                <h4 class="mb-0">{{ $users }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Today</p>
                                <h4 class="mb-0">{{ $todayleads }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center ">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total</p>
                                <h4 class="mb-0">{{ $customers }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center ">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>
<!-- end row -->

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
                                <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Branches Collection</h4>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Branch Name</th>
                                <th class="align-middle">Today Collection</th>
                                <th class="align-middle">Total Collection</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $todayCollection[$branch->id] ?? 0; }}</td>
                                <td>{{ $totalCollection[$branch->id] ?? 0; }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Today Payment</h4>
                @if(count($todayPayment)>0)
                <div class="table-responsive">
                    <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
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
                            @foreach ($todayPayment as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                        class="btn btn-outline-primary waves-effect waves-light"><i
                                            class="fa fa-edit"></i></a>
                                    <a href="#" onclick="openPaymentModal({{ $customer->id }})"
                                        class="btn btn-outline-info waves-effect waves-light">
                                        <!-- <i class="fa fa-list"></i> -->Pay
                                    </a>
                                </td>
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
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Old Payment</h4>
                @if(count($oldPendingPayment)>0)
                <div class="table-responsive">
                    <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Link</th>
                                <th>Package</th>
                                <th>Package Amount</th>
                                <th>Advanced</th>
                                <th>Balance</th>
                                <th>Due Date</th>
                                <th>Branch</th>
                                <th>User</th>
                                <th>Kid Name</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Whatsapp No</th>
                                <th>Verified</th>
                                <th>Verified At</th>
                                <th>Address</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($oldPendingPayment as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                        class="btn btn-outline-primary waves-effect waves-light"><i
                                            class="fa fa-edit"></i></a>
                                    <a href="#" onclick="openPaymentModal({{ $customer->id }})"
                                        class="btn btn-outline-info waves-effect waves-light">
                                        <!-- <i class="fa fa-list"></i> -->Pay
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="{{ URL::to('show/' . $customer->link) }}" target="_blank"><u>Link</u></a>
                                    <br>
                                    <button class="btn btn-sm btn-outline-secondary ms-2 copy-btn"
                                        data-link="{{ URL::to('show/' . $customer->link) }}">
                                        Copy
                                    </button>
                                </td>
                                <td>{{ $customer->package }}</td>
                                <td>{{ $customer->package_amount }}</td>
                                <td>{{ $customer->advanced }}</td>
                                <td>{{ $customer->balance }}</td>
                                <td>{{ !empty($customer->due_date) ? \Carbon\Carbon::parse($customer->due_date)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $customer->branches->name }}</td>
                                <td>{{ $customer->users->name }}</td>
                                <td>{{ $customer->kid_name }}</td>
                                <td>{{ $customer->father_name }}</td>
                                <td>{{ $customer->mother_name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->whatsapp_number }}</td>
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection