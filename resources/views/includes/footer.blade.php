<!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- form validation -->
{{-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
</script>

<!-- read more button in database -->
<script>
    $(document).ready(function() {
        function AddReadMore() {
            var carLmt = 30;
            var readMoreTxt = " ...read more";
            var readLessTxt = " read less";

            $(".add-read-more").each(function() {
                var content = $(this).text().trim();

                // Skip if already processed
                if ($(this).find(".first-section").length || content.length <= carLmt)
                    return;

                var firstSet = content.substring(0, carLmt);
                var secdHalf = content.substring(carLmt);

                var html = firstSet + "<span class='second-section'>" + secdHalf +
                    "</span><span class='read-more' title='Click to Show More'><u>" + readMoreTxt +
                    "</span><span class='read-less' title='Click to Show Less'><u>" + readLessTxt + "</u></span>";

                $(this).html(html);
            });

            // Toggle content
            $(document).on("click", ".read-more,.read-less", function() {
                $(this).closest(".add-read-more").toggleClass("show-less-content show-more-content");
            });
        }

        AddReadMore();
    });
</script>
<!-- read more button end in database -->

<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

<script>
    $(document).ready(function() {});
</script>

@yield('script')

<script>
    function openPaymentModal(customerId) {
        $('#customer_id').val(customerId);
        $('#payment_id').val('');
        $('#date').val(new Date().toISOString().split('T')[0]);
        $('#amount').val('');
        fetchPayments(customerId);
        $('#paymentModal').modal('show');
    }

    function fetchPayments(customerId) {
        $.get(`/payments/${customerId}`, function(response) {
            let rows = '';
            response.payments.forEach((payment, index) => {
                rows += `<tr>
            <td>${payment.date}</td>
            <td>${payment.amount}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editPayment(${payment.id})">Edit</button>
                ${
                    index > 0 
                    ? `<button class="btn btn-sm btn-danger" onclick="deletePayment(${payment.id})">Delete</button>` 
                    : ''
                }
            </td>
        </tr>`;
            });
            const balance = parseFloat(response.balance) || 0;
            $('#currentBalance').text(balance.toFixed(2));
            $('#paymentsTable tbody').html(rows);
        });
    }

    $('#paymentForm').submit(function(e) {
        e.preventDefault();

        const currentBalance = parseFloat($('#currentBalance').text()) || 0;
        const enteredAmount = parseFloat($('#amount').val()) || 0;
        const editPaymentId = $('#payment_id').val();
        let maxAllowed = currentBalance;

        if (editPaymentId) {
            // If editing, find the original payment value and add it to current balance
            $.get(`/payments/edit/${editPaymentId}`, function(payment) {
                maxAllowed += parseFloat(payment.amount);
                validateAndSubmit(maxAllowed, enteredAmount);
            });
        } else {
            // If adding new payment
            validateAndSubmit(maxAllowed, enteredAmount);
        }
    });

    function validateAndSubmit(maxAllowed, enteredAmount) {
        if (enteredAmount > maxAllowed) {
            alert(`Entered amount (${enteredAmount}) exceeds the allowed limit (${maxAllowed}).`);
            return;
        }

        const formData = $('#paymentForm').serialize();
        $.post(`/payments/save`, formData, function(response) {
            $('#payment_id').val('');
            $('#date').val(new Date().toISOString().split('T')[0]);
            $('#amount').val('');
            fetchPayments($('#customer_id').val());
        });
    }

    function clearAll() {
        $('#customer_id').val('');
        $('#payment_id').val('');
        $('#date').val(new Date().toISOString().split('T')[0]);
        $('#amount').val('');
    }

    function editPayment(id) {
        $.get(`/payments/edit/${id}`, function(payment) {
            $('#payment_id').val(payment.id);
            $('#date').val(payment.date);
            $('#amount').val(payment.amount);
        });
    }

    function deletePayment(id) {
        if (confirm('Are you sure to delete this payment?')) {
            $.ajax({
                url: `/payments/delete/${id}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    fetchPayments($('#customer_id').val());
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const link = this.getAttribute('data-link');
                navigator.clipboard.writeText(link).then(() => {
                    this.textContent = 'Copied!';
                    setTimeout(() => this.textContent = 'Copy', 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });
        });
    });
</script>

</body>

</html>