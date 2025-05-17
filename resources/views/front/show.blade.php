<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Documents Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Viewer.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.5/dist/viewer.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
        }

        th {
            font-weight: 600;
            padding: 8px 0;
            white-space: nowrap;
        }

        .th_second {
            font-weight: normal;
            color: #555;
        }

        .preview-item {
            cursor: zoom-in;
            max-height: 150px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .verified-msg {
            font-weight: bold;
            color: green;
            display: none;
        }

        .section-heading {
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body class="p-3 p-md-5">

    <div class="container">
        <h3 class="text-center mb-4 fw-bold">Documents Verification</h3>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h5 class="mb-3 text-primary">Parent Details</h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Kid's Name:</th>
                            <td class="th_second">{{ $customer->kid_name }}</td>
                        </tr>
                        <tr>
                            <th>Father Name:</th>
                            <td class="th_second">{{ $customer->father_name }}</td>
                        </tr>
                        <tr>
                            <th>Mother Name:</th>
                            <td class="th_second">{{ $customer->mother_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td class="th_second">{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile:</th>
                            <td class="th_second">{{ $customer->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Whatsapp No:</th>
                            <td class="th_second">{{ $customer->whatsapp_number }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td class="th_second">{{ $customer->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h5 class="mb-3 text-primary">Package Details</h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Package:</th>
                            <td class="th_second">{{ $customer->package }}</td>
                        </tr>
                        <tr>
                            <th>Package Amount:</th>
                            <td class="th_second">{{ $customer->package_amount }}</td>
                        </tr>
                        <tr>
                            <th>Advanced:</th>
                            <td class="th_second">{{ $customer->advanced }}</td>
                        </tr>
                        <tr>
                            <th>Balance:</th>
                            <td class="th_second">{{ $customer->balance }}</td>
                        </tr>
                        <tr>
                            <th>Due Date:</th>
                            <td class="th_second">{{ $customer->due_date }}</td>
                        </tr>
                        <tr>
                            <th>Remark:</th>
                            <td class="th_second">{{ $customer->remark }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <h4 class="text-center section-heading">Uploaded Documents</h4>
        <div id="preview-container" class="row g-4">
            @foreach ($documents as $document)
            @php $ext = pathinfo($document->image, PATHINFO_EXTENSION); @endphp

            <div class="col-6 col-sm-4 col-md-3">
                @if (in_array($ext, ['png', 'jpg', 'jpeg']))
                <img src="{{ asset('documents/' . $document->image) }}" class="img-fluid preview-item"
                    alt="Image">
                @elseif ($ext === 'pdf')
                <div class="doc-preview" data-type="pdf"
                    data-url="{{ asset('documents/' . $document->image) }}">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                        class="img-fluid preview-item" alt="PDF">
                </div>
                @elseif (in_array($ext, ['doc', 'docx']))
                <div class="doc-preview" data-type="doc"
                    data-url="{{ asset('documents/' . $document->image) }}">
                    <img src="https://cdn-icons-png.flaticon.com/512/716/716784.png"
                        class="img-fluid preview-item" alt="Word">
                </div>
                @else
                <div class="doc-preview" data-type="other" data-url="{{ asset('documents/' . $document->image) }}">
                    <img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" class="img-fluid preview-item" alt="File">
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Verification Action -->
        <div class="text-center mt-5">
            @if ($customer->is_verified == 0)
            <button id="verifyBtn" class="btn btn-success px-4 py-2">
                <span class="btn-text">Verify</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
            @else
            <div class="mt-3 text-success fw-bold">
                Documents already Verified -
                {{ \Carbon\Carbon::parse($customer->verified_at)->format('d-m-Y H:i:s') }}
            </div>
            @endif
            <div id="verifiedMsg" class="verified-msg mt-3">Documents Verified Succesfully</div>
        </div>
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

    <!-- (Keep your modals and scripts unchanged) -->
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
                $iframe.attr('src', url);
            } else if (type === 'doc' || type === 'docx') {
                $iframe.attr('src', `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`);
            } else if (type === 'other') {
                // Option 1: Try to open in iframe (for .txt, .csv)
                const viewable = ['txt', 'csv', 'html'];
                const extension = url.split('.').pop().toLowerCase();

                if (viewable.includes(extension)) {
                    $iframe.attr('src', url);
                    const modalEl = document.getElementById('docPreviewModal');
                    const docModal = new bootstrap.Modal(modalEl);
                    docModal.show();
                } else {
                    // Option 2: Trigger download for non-previewable files
                    window.open(url, '_blank');
                }
            }

            if (type !== 'other') {
                const modalEl = document.getElementById('docPreviewModal');
                const docModal = new bootstrap.Modal(modalEl);
                docModal.show();
            }
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
                    var otpModalEl = document.getElementById('otpModal');
                    if (otpModalEl) {
                        var otpModal = new bootstrap.Modal(otpModalEl);
                        otpModal.show();
                    } else {
                        alert("Modal element not found");
                    }
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