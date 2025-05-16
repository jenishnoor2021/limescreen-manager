@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Package</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                {!! Form::model($package, [
                'method' => 'PATCH',
                'action' => ['AdminPackageController@update', $package->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editPackageForm',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Package Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter name" value="{{ $package->name }}" required>
                            @if ($errors->has('name'))
                            <div class="error text-danger">{{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount">Amount<span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" id="amount"
                                placeholder="Enter amount" value="{{ $package->amount }}" required>
                            @if ($errors->has('amount'))
                            <div class="error text-danger">{{ $errors->first('amount') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/package') }}">Back</a>
                </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {
        $("form[name='editPackageForm']").validate({
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