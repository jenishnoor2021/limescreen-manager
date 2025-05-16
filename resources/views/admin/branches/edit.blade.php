@extends('layouts.admin')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Branch</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    {!! Form::model($branch, [
                        'method' => 'PATCH',
                        'action' => ['AdminBranchController@update', $branch->id],
                        'files' => true,
                        'class' => 'form-horizontal',
                        'name' => 'editBranchForm',
                    ]) !!}
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Branch Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Enter name" value="{{ $branch->name }}" required>
                                @if ($errors->has('name'))
                                    <div class="error text-danger">{{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-md">update</button>
                        <a class="btn btn-light w-md" href="{{ URL::to('/admin/branches') }}">Back</a>
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
            $("form[name='editBranchForm']").validate({
                rules: {
                    name: {
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
