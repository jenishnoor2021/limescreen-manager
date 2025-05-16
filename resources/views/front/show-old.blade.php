@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Top Row --}}
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="p-3 border rounded">Left Panel Content</div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded">Right Panel Content</div>
            </div>
        </div>

        {{-- Bottom Grid Row --}}
        <div class="row" id="media-grid">
            @foreach ($media as $item)
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="card h-100">
                        @if ($item['type'] === 'image')
                            <img src="{{ asset($item['path']) }}" class="card-img-top open-media"
                                data-src="{{ asset($item['path']) }}" alt="Image">
                        @else
                            <div class="card-body text-center">
                                <a href="{{ asset($item['path']) }}" target="_blank"
                                    class="btn btn-primary open-media"
                                    data-src="{{ asset($item['path']) }}">Open Document</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Verify Button --}}
        <div class="text-center mt-4" id="verify-section">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#otpModal">Verify</button>
        </div>

        {{-- Already Verified Message --}}
        <div class="text-center mt-4" id="verified-msg" style="display:none">
            <div class="alert alert-success">Already Verified</div>
        </div>
    </div>

    {{-- OTP Modal --}}
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="otpForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enter OTP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="otp" id="otpInput" class="form-control" required
                            placeholder="Enter OTP">
                        <input type="hidden" id="realOtp" value="123456"> {{-- For demo. Replace with backend logic. --}}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Verify</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let enteredOtp = document.getElementById('otpInput').value;
            let realOtp = document.getElementById('realOtp').value;

            if (enteredOtp === realOtp) {
                document.getElementById('verify-section').style.display = "none";
                document.getElementById('verified-msg').style.display = "block";
                let modal = bootstrap.Modal.getInstance(document.getElementById('otpModal'));
                modal.hide();
            } else {
                alert('Invalid OTP!');
            }
        });

        // Optional: click preview logic
        document.querySelectorAll('.open-media').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const src = item.dataset.src;
                window.open(src, '_blank');
            });
        });
    </script>
@endpush
