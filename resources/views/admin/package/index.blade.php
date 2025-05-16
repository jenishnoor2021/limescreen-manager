@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Package</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    Add Package
                </h4>

                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminPackageController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'packageForm',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="mb-3">
                        <label for="name">Package<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Enter package" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="amount">Amount<span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" id="amount"
                            placeholder="Enter amount" value="{{ old('amount') }}">
                        @if ($errors->has('amount'))
                        <div class="error text-danger">{{ $errors->first('amount') }}</div>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                </div>

                </form>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Package List</h4>

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Package Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($packages as $package)
                        <tr>
                            <td>
                                <a href="{{ route('admin.package.edit', $package->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.package.destroy', $package->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->amount }}</td>
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
    $(function() {
        $("form[name='packageForm']").validate({
            rules: {
                name: {
                    required: true,
                },
                amount: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection