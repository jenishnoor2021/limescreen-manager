<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verification Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Viewer.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.5/dist/viewer.min.css">
    <style>
        .verified-msg {
            font-weight: bold;
            color: green;
            display: none;
        }

        .preview-item {
            cursor: zoom-in;
            max-height: 150px;
            object-fit: contain;
        }

        .th_second {
            font-weight: normal;
        }

        .d-none {
            display: none;
        }
    </style>
</head>

<body class="p-5">

    <h4 class="text-center"><b>Documents verification</b></h4>
    <!-- Top Row -->
    <div class="row mb-4">
        <div class="col-md-6 border p-3">
            <table>
                <tr>
                    <th>Kid's Name:</th>
                    <th class="th_second">&nbsp;{{$customer->kid_name}}</th>
                </tr>
                <tr>
                    <th>Father Name:</th>
                    <th class="th_second">&nbsp;{{$customer->father_name}}</th>
                </tr>
                <tr>
                    <th>Mother Name:</th>
                    <th class="th_second">&nbsp;{{$customer->mother_name}}</th>
                </tr>
                <tr>
                    <th>Email:</th>
                    <th class="th_second">&nbsp;{{$customer->email}}</th>
                </tr>
                <tr>
                    <th>Mobile:</th>
                    <th class="th_second">&nbsp;{{$customer->mobile}}</th>
                </tr>
                <tr>
                    <th>Whatsapp No:</th>
                    <th class="th_second">&nbsp;{{$customer->whatsapp_number}}</th>
                </tr>
            </table>
        </div>
        <div class="col-md-6 border p-3">
            <table>
                <tr>
                    <th>Package:</th>
                    <th class="th_second">&nbsp;{{$customer->package}}</th>
                </tr>
                <tr>
                    <th>Package Amount:</th>
                    <th class="th_second">&nbsp;{{$customer->package_amount}}</th>
                </tr>
                <tr>
                    <th>Advanced:</th>
                    <th class="th_second">&nbsp;{{$customer->advanced}}</th>
                </tr>
                <tr>
                    <th>Balance:</th>
                    <th class="th_second">&nbsp;{{$customer->balance}}</th>
                </tr>
                <tr>
                    <th>Due Date:</th>
                    <th class="th_second">&nbsp;{{$customer->due_date}}</th>
                </tr>
                <tr>
                    <th>Address:</th>
                    <th class="th_second">&nbsp;{{$customer->address}}</th>
                </tr>
                <tr>
                    <th>Remark:</th>
                    <th class="th_second">&nbsp;{{$customer->remark}}</th>
                </tr>
            </table>
        </div>
    </div>

    <h4 class="text-center"><b>Documents</b></h4>
    <!-- Bottom Grid -->
    <div id="preview-container" class="row mb-4">
        @foreach ($documents as $document)
        @php
        $ext = pathinfo($document->image, PATHINFO_EXTENSION);
        @endphp

        @if (in_array($ext, ['png', 'jpg', 'jpeg']))
        <div class="col-md-3 mb-3">
            <img src="{{ asset('documents/' . $document->image) }}" class="img-fluid preview-item" alt="Image">
        </div>
        @elseif ($ext === 'pdf')
        <div class="col-md-3 mb-3">
            <div class="doc-preview" data-type="pdf" data-url="{{ asset('documents/' . $document->image) }}">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" class="img-fluid preview-item" alt="PDF">
            </div>
        </div>
        @elseif (in_array($ext, ['doc', 'docx']))
        <div class="col-md-3 mb-3">
            <div class="doc-preview" data-type="doc" data-url="{{ asset('documents/' . $document->image) }}">
                <img src="https://cdn-icons-png.flaticon.com/512/888/888879.png" class="img-fluid preview-item" alt="Word">
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <!-- Verified Button + Message -->
    <div class="text-center">
        @if($customer->is_verified == 0)
        <button id="verifyBtn" class="btn btn-success">
            <span class="btn-text">Verify</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
        @else
        <div class="mt-3" style="color:green;font-weight: bold;">Documents already Verified - {{ \Carbon\Carbon::parse($customer->verified_at)->format('d-m-Y H:i:s') }}</div>
        @endif
        <div id="verifiedMsg" class="verified-msg mt-3">Already Verified</div>
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="otpForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Verify OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe id="docFrame" style="width:100%; height:80vh;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/viewerjs@1.11.5/dist/viewer.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Initialize Viewer.js
        const container = document.getElementById('preview-container');
        const viewer = new Viewer(container, {
            filter: image => !image.closest('.doc-preview'), // Exclude DOC/PDF previews
        });

        // Handle PDF and DOC click
        $(document).on('click', '.doc-preview', function() {
            const type = $(this).data('type'); // pdf or doc
            const url = $(this).data('url');
            const $iframe = $('#docFrame');

            if (type === 'pdf') {
                $iframe.attr('src', url); // loads PDF directly
            } else if (type === 'doc') {
                $iframe.attr('src', `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`);
            }

            const modalEl = document.getElementById('docPreviewModal');
            const docModal = new bootstrap.Modal(modalEl);
            docModal.show();
        });

        // Handle Verify Button Click
        $('#verifyBtn').on('click', function() {
            const $btn = $(this);
            const $text = $btn.find('.btn-text');
            const $spinner = $btn.find('.spinner-border');

            $text.addClass('d-none');
            $spinner.removeClass('d-none');
            // AJAX call to send OTP (simulate here)
            $.ajax({
                url: '/send-otp', // Replace with your actual backend endpoint
                type: 'POST',
                data: {
                    mobile: <?= $customer->mobile ?>,
                    _token: '{{ csrf_token() }}' // ✅ CSRF token for Laravel
                },
                success: function(response) {
                    // OTP sent, show modal
                    new bootstrap.Modal(document.getElementById('otpModal')).show();
                },
                error: function() {
                    alert('Failed to send OTP');
                },
                complete: function() {
                    // Hide spinner, show text again (always runs after success or error)
                    $spinner.addClass('d-none');
                    $text.removeClass('d-none');
                }
            });
        });

        // Handle OTP Submit
        $('#otpForm').on('submit', function(e) {
            e.preventDefault();
            const enteredOtp = $('#otpInput').val();

            // AJAX call to verify OTP (simulate here)
            $.ajax({
                url: '/verify-otp', // Replace with your actual backend endpoint
                type: 'POST',
                data: {
                    id: <?= $customer->id ?>,
                    otp: enteredOtp,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#otpModal').modal('hide');
                        $('#verifyBtn').hide();
                        $('#verifiedMsg').show();
                    } else {
                        alert('Invalid OTP');
                    }
                },
                error: function() {
                    $('#otpInput').val('');
                    alert('OTP verification failed');
                }
            });
        });
    </script>
</body>

</html>