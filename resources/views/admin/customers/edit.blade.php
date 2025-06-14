@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit</h4>

                {!! Form::model($customer, [
                'method' => 'PATCH',
                'action' => ['AdminCustomerController@update', $customer->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'customerEditForm',
                ]) !!}
                @csrf

                @if (Session::get('user')['role'] == 'Manager')
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
                                <option value="{{ $branch->id }}"
                                    {{ (string) $branch->id === (string) $customer->branches_id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
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
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $user->id == $customer->users_id ? 'selected' : '' }}>{{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('users_id'))
                            <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="kid_name" class="form-label">Kid's Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="kid_name" class="form-control" id="kid_name"
                                placeholder="Enter your child name" value="{{ $customer->kid_name }}" required>
                            @if ($errors->has('kid_name'))
                            <div class="error text-danger">{{ $errors->first('kid_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="father_name" class="form-label">Father name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control" id="father_name"
                                placeholder="Enter father name" value="{{ $customer->father_name }}" required>
                            @if ($errors->has('father_name'))
                            <div class="error text-danger">{{ $errors->first('father_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mother_name" class="form-label">Mother name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="mother_name" class="form-control" id="mother_name"
                                placeholder="Enter mother name" value="{{ $customer->mother_name }}" required>
                            @if ($errors->has('mother_name'))
                            <div class="error text-danger">{{ $errors->first('mother_name') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter email" value="{{ $customer->email }}" required>
                            @if ($errors->has('email'))
                            <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile no<span class="text-danger">*</span></label>
                            <input type="number" name="mobile" class="form-control" id="mobile"
                                placeholder="Enter number" value="{{ $customer->mobile }}" required>
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">Whatsapp number<span
                                    class="text-danger">*</span></label>
                            <input type="number" name="whatsapp_number" class="form-control" id="whatsapp_number"
                                placeholder="Enter whatsapp number" value="{{ $customer->whatsapp_number }}"
                                required>
                            @if ($errors->has('whatsapp_number'))
                            <div class="error text-danger">{{ $errors->first('whatsapp_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Record created date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="created_at" class="form-control" id="created_at"
                                placeholder="Enter date" value="{{ \Carbon\Carbon::parse($customer->created_at)->format('Y-m-d') }}" required>
                            @if ($errors->has('created_at'))
                            <div class="error text-danger">{{ $errors->first('created_at') }}</div>
                            @endif
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                            <select name="status" id="status1" class="form-select" required>
                                <option value="NewLead" {{ $customer->status == 'NewLead' ? 'selected' : '' }}>New
                    Lead</option>
                    <option value="Visited" {{ $customer->status == 'Visited' ? 'selected' : '' }}>Visited
                    </option>
                    <option value="PhotoReceived"
                        {{ $customer->status == 'PhotoReceived' ? 'selected' : '' }}>Photo Received
                    </option>
                    <option value="Interested" {{ $customer->status == 'Interested' ? 'selected' : '' }}>
                        Interested</option>
                    <option value="NotInterested"
                        {{ $customer->status == 'NotInterested' ? 'selected' : '' }}>Not interested
                    </option>
                    </select>
                    @if ($errors->has('status'))
                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                    @endif
                </div>
            </div> --}}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea type="text" name="remark" class="form-control" id="remark" placeholder="Enter remark">{{ $customer->remark }}</textarea>
                    @if ($errors->has('remark'))
                    <div class="error text-danger">{{ $errors->first('remark') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ $customer->address }}</textarea>
                    @if ($errors->has('address'))
                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="package" class="form-label">Package<span
                            class="text-danger">*</span></label>
                    <select name="package" id="package" class="form-select" required>
                        <option value="">Select package</option>
                        @foreach ($packages as $package)
                        <option value="{{ $package->name }}" data-amount="{{ $package->amount }}"
                            {{ $customer->package == $package->name ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                        @endforeach
                    </select>
                    @if ($errors->has('package'))
                    <div class="error text-danger">{{ $errors->first('package') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="package_amount" class="form-label">Package Amount<span
                            class="text-danger">*</span></label>
                    <input type="text" name="package_amount" class="form-control" id="package_amount"
                        value="{{ $customer->package_amount }}" placeholder="Enter package amount" required>
                    @if ($errors->has('package_amount'))
                    <div class="error text-danger">{{ $errors->first('package_amount') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label for="advanced" class="form-label">Amount received<span
                            class="text-danger">*</span></label>
                    <input type="text" name="advanced" class="form-control" id="advanced"
                        value="{{ $customer->advanced }}" placeholder="Enter advanced amount" readonly>
                    @if ($errors->has('advanced'))
                    <div class="error text-danger">{{ $errors->first('advanced') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label for="balance" class="form-label">Balance<span
                            class="text-danger">*</span></label>
                    <input type="text" name="balance" class="form-control" id="balance"
                        value="{{ $customer->balance }}" placeholder="Enter balance" readonly>
                    @if ($errors->has('balance'))
                    <div class="error text-danger">{{ $errors->first('balance') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date<span
                            class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control" id="due_date"
                        value="{{ $customer->due_date }}" placeholder="Enter due date" required>
                    @if ($errors->has('due_date'))
                    <div class="error text-danger">{{ $errors->first('due_date') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div id="existing-images" class="row">
            @foreach ($customerDocs as $key => $doc)
            @php
            $ext = strtolower(pathinfo($doc->image, PATHINFO_EXTENSION));
            $fileUrl = asset('documents/' . $doc->image);
            @endphp

            <div class="col-6 col-sm-4 col-md-3 mb-4">
                <div class="border rounded p-2 position-relative text-center existing-image-box" data-id="{{ $doc->id }}">
                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ $fileUrl }}" class="img-fluid preview-item" alt="Image">
                    @elseif ($ext === 'pdf')
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" class="img-fluid preview-item" alt="PDF">
                    @elseif (in_array($ext, ['doc', 'docx']))
                    <img src="https://cdn-icons-png.flaticon.com/512/716/716784.png" class="img-fluid preview-item" alt="Word">
                    @else
                    <img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" class="img-fluid preview-item" alt="File">
                    @endif

                    <input type="hidden" name="existing_images[]" value="{{ $doc->image }}">

                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-existing-image w-100">
                        Remove
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Add More Files</label>
            <div id="imageInputContainer"></div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-info" id="addImageInput">Add Image</button>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-md">Update</button>
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
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='customerEditForm']").validate({
            rules: {
                kid_name: {
                    required: true,
                },
                father_name: {
                    required: true,
                },
                mother_name: {
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
                // },
                package: {
                    required: true,
                },
                package_amount: {
                    required: true,
                },
                advanced: {
                    required: true,
                },
                balance: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
                created_at: {
                    required: true,
                }
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

                        if (loginRole !== 'Manager') {
                            $('#users_id').append('<option value="ALL">ALL</option>');
                        }

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
    // document.getElementById('mobile').addEventListener('input', function() {
    //     this.value = this.value.replace(/\D/g, '').slice(0, 10);
    // });
    // document.getElementById('whatsapp_number').addEventListener('input', function() {
    //     this.value = this.value.replace(/\D/g, '').slice(0, 10);
    // });
</script>
<script>
    let inputCount = 1;

    @foreach($customerDocs as $key => $doc)
    inputCount++;
    @endforeach

    // Add file input dynamically
    document.getElementById('addImageInput').addEventListener('click', function() {
        const index = inputCount++;
        const container = document.createElement('div');
        container.className = 'image-block mb-3';
        container.setAttribute('data-index', index);
        container.innerHTML = `
                <input type="file" name="file[]" class="form-control image-input" id="image_${index}">
                <div class="mt-2">
                    <div id="preview_${index}" style="width: 150px; border: 1px solid #ccc;"></div>
                    <button type="button" class="btn btn-danger btn-sm removeImage mt-1">Remove</button>
                </div>
            `;
        document.getElementById('imageInputContainer').appendChild(container);
        toggleRemoveButtons();
    });

    // Preview selected file (image or PDF)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            const index = e.target.id.split('_')[1];
            const file = e.target.files[0];

            if (file) {
                const previewBox = document.getElementById(`preview_${index}`);
                const fileType = file.type;
                const reader = new FileReader();

                reader.onload = function(event) {
                    if (fileType.startsWith('image/')) {
                        previewBox.innerHTML = `<img src="${event.target.result}" style="width: 150px;" />`;
                    } else if (fileType === 'application/pdf') {
                        previewBox.innerHTML =
                            `<embed src="${event.target.result}" type="application/pdf" width="150" height="200" />`;
                    } else {
                        previewBox.innerHTML = `<span class="text-danger">Unsupported file type</span>`;
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Remove input block
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeImage')) {
            e.target.closest('.image-block').remove();
            toggleRemoveButtons();
        }

        if (e.target.classList.contains('remove-existing-image')) {
            e.target.closest('.existing-image-box').remove();
        }
    });

    // Hide remove if only one
    function toggleRemoveButtons() {
        const removeButtons = document.querySelectorAll('.removeImage');
        if (removeButtons.length <= 1) {
            removeButtons.forEach(btn => btn.style.display = 'none');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'inline-block');
        }
    }

    // Optional: add one input by default
    document.getElementById('addImageInput').click();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const packageSelect = document.getElementById('package');
        const packageAmountInput = document.getElementById('package_amount');
        const advancedInput = document.getElementById('advanced');
        const balanceInput = document.getElementById('balance');

        function calculateBalance() {
            const packageAmount = parseFloat(packageAmountInput.value) || 0;
            const advanced = parseFloat(advancedInput.value) || 0;
            const balance = packageAmount - advanced;
            balanceInput.value = balance.toFixed(2); // keep two decimal points
        }

        packageSelect.addEventListener('change', function() {
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const amount = selectedOption.getAttribute('data-amount');

            if (amount) {
                packageAmountInput.value = amount;
                calculateBalance();
            } else {
                packageAmountInput.value = '';
                balanceInput.value = '';
            }
        });

        packageAmountInput.addEventListener('input', calculateBalance);
        advancedInput.addEventListener('input', calculateBalance);
    });
</script>
@endsection