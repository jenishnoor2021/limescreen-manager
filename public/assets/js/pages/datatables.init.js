$(document).ready(function(){$("#datatable").DataTable({
        pageLength: 500,
        ordering: false
    }),$("#datatable-buttons").DataTable({pageLength: 500,
        ordering: false,
        lengthChange: false,
        buttons:["copy","excel","pdf","colvis"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});