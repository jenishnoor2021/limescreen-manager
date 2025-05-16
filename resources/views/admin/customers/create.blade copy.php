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
            <h4 class="mb-sm-0 font-size-18">ADD</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="card-title mb-4">ADD</h4> -->

                @if (session()->has('message'))
                <div class="alert text-white" style="background-color:#7EDD72">
                    {{ session()->get('message') }}
                </div>
                @endif

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminCustomerController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'customerAddForm',
                ]) !!}
                @csrf

                @if (Session::get('user')['role'] != 'Admin')
                <input type="hidden" name="users_id" value="{{ Session::get('user')['id'] }}">
                <input type="hidden" name="branches_id" value="{{ Session::get('user')['branches_id'] }}">
                @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="branches_id">Branch<span class="text-danger">*</span></label>
                            <select name="branches_id" id="branches_id" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('branches_id'))
                            <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="users_id">Manager<span class="text-danger">*</span></label>
                            <select name="users_id" id="users_id" class="form-select" required>
                                <option value="">Select Manager</option>
                            </select>
                            @if ($errors->has('users_id'))
                            <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="child_name" class="form-label">Kid's Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="child_name" class="form-control" id="child_name"
                                placeholder="Enter your child name" value="{{ old('child_name') }}" required>
                            @if ($errors->has('child_name'))
                            <div class="error text-danger">{{ $errors->first('child_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="father_name" class="form-label">Parents name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control" id="father_name"
                                placeholder="Enter father name" required>
                            @if ($errors->has('father_name'))
                            <div class="error text-danger">{{ $errors->first('father_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mother_name" class="form-label">Mother's name<span class="text-danger">*</span></label>
                            <input type="text" name="mother_name" class="form-control" id="mother_name"
                                placeholder="Enter mother name" required>
                            @if ($errors->has('mother_name'))
                            <div class="error text-danger">{{ $errors->first('mother_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter email" required>
                            @if ($errors->has('email'))
                            <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile no<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" maxlength="10"
                                pattern="\d{10}" placeholder="Enter number" title="Enter exactly 10 digits" required>
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">Whatsapp number<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="whatsapp_number" class="form-control" id="whatsapp_number"
                                maxlength="10" pattern="\d{10}" placeholder="Enter whatsapp number"
                                title="Enter exactly 10 digits" required>
                            @if ($errors->has('whatsapp_number'))
                            <div class="error text-danger">{{ $errors->first('whatsapp_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                            <select name="status" id="status1" class="form-select" required>
                                <option value="NewLead">New Lead</option>
                                <option value="Visited">Visited</option>
                                <option value="PhotoReceived">Photo Received</option>
                                <option value="Interested">Interested</option>
                                <option value="NotInterested">Not interested</option>
                            </select>
                            @if ($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea type="text" name="remark" class="form-control" id="remark" placeholder="Enter remark">{{ old('remark') }}</textarea>
                            @if ($errors->has('remark'))
                            <div class="error text-danger">{{ $errors->first('remark') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address') }}</textarea>
                            @if ($errors->has('address'))
                            <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Package<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" maxlength="10"
                                pattern="\d{10}" placeholder="Enter number" title="Enter exactly 10 digits" required>
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">Package Amount<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="whatsapp_number" class="form-control" id="whatsapp_number"
                                maxlength="10" pattern="\d{10}" placeholder="Enter whatsapp number"
                                title="Enter exactly 10 digits" required>
                            @if ($errors->has('whatsapp_number'))
                            <div class="error text-danger">{{ $errors->first('whatsapp_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">Amount received<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" maxlength="10"
                                pattern="\d{10}" placeholder="Enter number" title="Enter exactly 10 digits" required>
                            @if ($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">Balance<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" id="mobile" maxlength="10"
                                pattern="\d{10}" placeholder="Enter number" title="Enter exactly 10 digits" required>
                            @if ($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cropped_images" class="form-label">Phone of agreement</label>
                    <input type="file" name="file[]" class="form-control image-input">
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-info" id="addImageInput">Add Image</button>
                </div>

                <input type="hidden" name="cropped_images[]" id="cropped_images_0">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/customers') }}">Back</a>
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

        $("form[name='customerAddForm']").validate({
            rules: {
                child_name: {
                    required: true,
                },
                parent_name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                whatsapp_number: {
                    required: true,
                },
                // address: {
                //     required: true,
                // }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#branches_id').change(function() {
            var branchId = $(this).val();
            if (branchId) {
                $.ajax({
                    url: '/get-users-by-branch/' + branchId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#users_id').empty();
                        $('#users_id').append('<option value="">Select User</option>');
                        $.each(data, function(key, user) {
                            $('#users_id').append('<option value="' + user.id +
                                '">' + user.name + '</option>');
                        });
                    }
                });
            } else {
                $('#users_id').empty().append('<option value="">Select User</option>');
            }
        });
    });
</script>
<script>
    document.getElementById('mobile').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
    document.getElementById('whatsapp_number').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"
    integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let inputCount = 1;
    let cropper;
    let currentInputId = null;

    var $modal = $('#modal');
    var image = document.getElementById('sample_image');

    document.getElementById('addImageInput').addEventListener('click', function() {
        const index = inputCount++;
        const container = document.createElement('div');
        container.className = 'image-block mb-3';
        container.setAttribute('data-index', index);
        container.innerHTML = `
            <input type="file" name="file[]" class="form-control image-input" id="image_${index}" accept="image/*">
            <input type="hidden" name="cropped_images[]" id="cropped_images_${index}">
            <div class="mt-2">
                <img id="preview_${index}" class="hidden" style="width: 150px; border: 1px solid #ccc;" />
                <button type="button" class="btn btn-danger btn-sm removeImage mt-1">Remove</button>
            </div>
        `;
        document.getElementById('imageInputContainer').appendChild(container);
        toggleRemoveButtons();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            const index = e.target.id.split('_')[1];
            currentInputId = index;
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    image.src = event.target.result;
                    if (cropper) cropper.destroy();
                    $modal.modal('show');
                    setTimeout(() => {
                        cropper = new Cropper(image, {
                            aspectRatio: NaN,
                            viewMode: 0,
                            preview: '.preview',
                        });
                    }, 200);
                };
                reader.readAsDataURL(file);
            }
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
        if (!cropper || currentInputId === null) return;

        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob(blob => {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                const base64data = reader.result;
                document.getElementById(`cropped_images_${currentInputId}`).value = base64data;
                const preview = document.getElementById(`preview_${currentInputId}`);
                preview.src = base64data;
                preview.classList.remove('hidden');
                $modal.modal('hide');
                cropper.destroy();
                cropper = null;
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg');
    };

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeImage')) {
            e.target.closest('.image-block').remove();
            toggleRemoveButtons();
        }
    });

    function toggleRemoveButtons() {
        const removeButtons = document.querySelectorAll('.removeImage');
        if (removeButtons.length <= 1) {
            removeButtons.forEach(btn => btn.style.display = 'none');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'inline-block');
        }
    }

    document.getElementById('addImageInput').click();

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