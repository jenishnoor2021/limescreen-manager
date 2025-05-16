@extends('layouts.admin')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"
    integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 800px !important;
        max-height: 800px !important;
    }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">ADD User</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">ADD</h4>

                @if (session()->has('message'))
                <div class="alert text-white" style="background-color:#7EDD72">
                    {{ session()->get('message') }}
                </div>
                @endif

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'userAddForm',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="role">Role<span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="User">User</option>
                                @if (Session::get('user')['role'] == 'Admin')
                                <option value="Manager">Manager</option>
                                @endif
                            </select>
                            @if ($errors->has('role'))
                            <div class="error text-danger">{{ $errors->first('role') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="branches_id">Branch<span class="text-danger">*</span></label>
                            <select name="branches_id" id="branches_id" class="form-select" required>
                                <!-- <option value="">Select Branch</option> -->
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('branches_id'))
                            <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter Your Name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                            <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter Your Email" onkeypress='return (event.charCode != 32)'
                                value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                            <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile no<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" maxlength="10"
                                pattern="\d{10}" placeholder="Enter number" title="Enter exactly 10 digits">
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address') }}</textarea>
                    @if ($errors->has('address'))
                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Enter username" onkeypress='return (event.charCode != 32)'
                                value="{{ old('username') }}" required>
                            @if ($errors->has('username'))
                            <div class="error text-danger">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password<span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Enter password" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                            <div class="error text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="file" class="form-label">Image<span class="text-danger">*</span></label>
                            <input type="file" name="file[]" class="form-control" id="image" accept="image"
                                required>
                            @if ($errors->has('file'))
                            <div class="error text-danger">{{ $errors->first('file') }}</div>
                            @endif
                        </div>
                        <input type="hidden" name="cropped_image" id="cropped_image_input">
                        <div id="imagePreviewContainer" class="mt-3 hidden">
                            <img id="imagePreview" src="#" alt="Image Preview" class="hidden"
                                style="width: 200px; height: auto; border: 1px solid #ccc; padding: 5px;" />
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/users') }}">Back</a>
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

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" />
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="zoomin" class="btn btn-primary">Zoom In</button>
                <button type="button" id="zoomout" class="btn btn-primary">Zoom Out</button>
                <button type="button" id="rotateleft" class="btn btn-primary">rotate Left</button>
                <button type="button" id="rotateright" class="btn btn-primary">rotate Right</button>
                <button type="button" id="scalex" class="btn btn-primary">Scale X</button>
                <button type="button" id="scaley" class="btn btn-primary">Scale Y</button>
                <br><br>
                <button type="button" id="aspres169" class="btn btn-primary">16:9</button>
                <button type="button" id="aspres43" class="btn btn-primary">4:3</button>
                <button type="button" id="aspres11" class="btn btn-primary">1:1</button>
                <button type="button" id="aspres23" class="btn btn-primary">2:3</button>
                <button type="button" id="aspresfree" class="btn btn-primary">free</button>
                <button type="button" id="crop" class="btn btn-primary">Crop</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='userAddForm']").validate({
            rules: {
                role: {
                    required: true,
                },
                name: {
                    required: true,
                },
                username: {
                    required: true,
                },
                // address: {
                //     required: true,
                // },
                mobile: {
                    required: true,
                },
                password: {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script>
    document.getElementById('mobile').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"
    integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let cropper;
    var $modal = $('#modal');
    var image = document.getElementById('sample_image');

    document.getElementById('image').addEventListener('change', function(event) {

        var files = event.target.files;

        const done = function(url) {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            image.src = url;
            $modal.modal('show');

            setTimeout(() => {
                cropper = new Cropper(image, {
                    aspectRatio: NaN,
                    viewMode: 0,
                    preview: '.preview',
                    movable: true,
                });
            }, 200);
        };

        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(event) {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }

    });

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            document.getElementById('modal').classList.add('hidden');
            $modal.modal('hide');
        });
    });

    document.getElementById('crop').onclick = () => {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob(blob => {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                const base64data = reader.result;
                document.getElementById('imagePreview').src = base64data;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('cropped_image_input').value = base64data;
                $modal.modal('hide');
            };
        }, 'image/jpeg');
    };

    document.getElementById('zoomin').onclick = () => cropper?.zoom(0.1);
    document.getElementById('zoomout').onclick = () => cropper?.zoom(-0.1);
    document.getElementById('rotateleft').onclick = () => cropper?.rotate(-45);
    document.getElementById('rotateright').onclick = () => cropper?.rotate(45);
    document.getElementById('scalex').onclick = () => cropper?.scaleX(-1);
    document.getElementById('scaley').onclick = () => cropper?.scaleY(-1);
    document.getElementById('aspres169').onclick = () => cropper?.setAspectRatio(16 / 9);
    document.getElementById('aspres43').onclick = () => cropper?.setAspectRatio(4 / 3);
    document.getElementById('aspres11').onclick = () => cropper?.setAspectRatio(1);
    document.getElementById('aspres23').onclick = () => cropper?.setAspectRatio(2 / 3);
    document.getElementById('aspresfree').onclick = () => cropper?.setAspectRatio(NaN);
</script>
@endsection