@extends('layouts.admin')
@section('content')
@section('style')
<style>
    .form-check-input {
        width: 2em;
        height: 1em;
        background-color: #e4e4e4;
        border-radius: 1em;
        position: relative;
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    .form-check-input:checked {
        background-color: #4caf50;
    }

    .form-check-input::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 0.8em;
        height: 0.8em;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s ease-in-out;
    }

    .form-check-input:checked::before {
        transform: translateX(1em);
    }
</style>
@endsection
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Manager List</h4>
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
                            <div class="">
                                <a class="btn btn-info waves-effect waves-light"
                                    href="{{ route('admin.users.create') }}"><i class="fa fa-plus editable"
                                        style="font-size:15px;">&nbsp;ADD</i></a>
                            </div>
                        </span>

                    </div>
                </div>



                <table id="datatable" class="table table-bordered dt-responsive w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Active/De-active</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                @if (Session::get('user')['role'] != 'User')
                                <a href="{{ route('admin.users.destroy', $user->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                                @endif
                            </td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->branches->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>
                                <p class="add-read-more show-less-content">{{ $user->address }}</p>
                            </td>
                            <td>
                                @if (Session::get('user')['role'] != 'User')
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status-switch" type="checkbox"
                                        id="toggleSwitch{{ $user->id }}" data-id="{{ $user->id }}"
                                        {{ $user->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="toggleSwitch{{ $user->id }}">
                                        {{ $user->is_active ? 'Active' : 'De-active' }}
                                    </label>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script>
    $(document).on('change', '.toggle-status-switch', function() {
        const toggleSwitch = $(this);
        const agentId = toggleSwitch.data('id');
        const isChecked = toggleSwitch.is(':checked');

        $.ajax({
            url: "{{ route('admin.users.active') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: agentId
            },
            success: function(response) {
                if (response.success) {
                    toggleSwitch.next('label').text(response.status);
                    alert('Status updated successfully');
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                // Revert toggle state in case of an error
                toggleSwitch.prop('checked', !isChecked);
            }
        });
    });
</script>
@endsection