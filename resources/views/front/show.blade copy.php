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
        {{ $document->image }}
        <br>
        @endforeach
        <div class="col-md-3 mb-3">
            <img src="http://127.0.0.1:8000/documents/document_1747201892_0.png" class="img-fluid preview-item"
                alt="Image 1">
        </div>
        <div class="col-md-3 mb-3">
            <img src="http://127.0.0.1:8000/documents/document_1747205197_0.png" class="img-fluid preview-item"
                alt="Image 2">
        </div>
        <!-- Add more images/documents -->

        <div class="col-md-3 mb-3">
            <div class="doc-preview" data-type="pdf"
                data-url="https://8bloqs.s3.us-west-2.amazonaws.com/4000-6385/sample-1744285312.pdf">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" class="img-fluid preview-item"
                    alt="PDF Doc">
            </div>
        </div>

        <!-- Grid Item for Word DOC -->
        <div class="col-md-3 mb-3">
            <div class="doc-preview" data-type="doc" data-url="{{ asset('documents/test-doc.docx') }}">
                <img src="https://cdn-icons-png.flaticon.com/512/888/888879.png" class="img-fluid preview-item"
                    alt="Word Doc">
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="doc-preview" data-type="pdf"
                data-url="https://8bloqs.s3.us-west-2.amazonaws.com/4000-6385/sample-1744285312.pdf">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" class="img-fluid preview-item"
                    alt="PDF Doc">
            </div>
        </div>

    </div>

    <!-- Verified Button + Message -->
    <div class="text-center">
        <button id="verifyBtn" class="btn btn-success">Verify</button>
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
                    <iframe id="docFrame" style="width:100%; height:90vh;" frameborder="0"></iframe>
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
            const type = $(this).data('type');
            const url = $(this).data('url');
            const $iframe = $('#docFrame');

            if (type === 'pdf') {
                $iframe.attr('src', url);
            } else if (type === 'doc') {
                // Google Docs Viewer for DOC
                $iframe.attr('src', `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`);
            }

            const modalEl = document.getElementById('docPreviewModal');
            const docModal = new bootstrap.Modal(modalEl);
            docModal.show();
        });

        // $(document).on('click', '.doc-preview', function() {
        //     const type = $(this).data('type');
        //     const url = $(this).data('url');
        //     const $iframe = $('#docFrame');
        //     const modalEl = document.getElementById('docPreviewModal');
        //     const docModal = new bootstrap.Modal(modalEl);

        //     if (type === 'pdf') {
        //         $iframe.attr('src', url);
        //     } else if (type === 'doc') {
        //         $iframe.attr('src', `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`);
        //     }

        //     docModal.show();
        // });


        // Handle Verify Button Click
        $('#verifyBtn').on('click', function() {
            // AJAX call to send OTP (simulate here)
            $.ajax({
                url: '/send-otp', // Replace with your actual backend endpoint
                type: 'POST',
                data: {
                    mobile: <?= $customer->mobile ?>
                },
                success: function(response) {
                    // OTP sent, show modal
                    new bootstrap.Modal(document.getElementById('otpModal')).show();
                },
                error: function() {
                    alert('Failed to send OTP');
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
                    otp: enteredOtp
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
                    alert('OTP verification failed');
                }
            });
        });
    </script>
</body>

</html>