<meta charset="utf-8" />
<title>Login | Skote - Admin & Dashboard Template</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

<!-- DataTables -->
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />

<!-- Bootstrap Css -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" id="app-style" type="text/css">

<style>
    .boldclass {
        font-weight: bold;
    }

    .add-read-more {
        max-width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
        box-sizing: border-box;
        /* padding: 10px; */
    }

    .add-read-more .second-section {
        display: inline;
    }

    @media screen and (max-width: 600px) {
        .add-read-more {
            font-size: 14px;
        }
    }

    .add-read-more.show-less-content .second-section,
    .add-read-more.show-less-content .read-less {
        display: none;
    }

    .add-read-more.show-more-content .read-more {
        display: none;
    }

    .add-read-more .read-more,
    .add-read-more .read-less {
        font-weight: bold;
        margin-left: 2px;
        color: blue;
        cursor: pointer;
    }

    .error {
        color: red;
    }
</style>
@yield('style')

<script src="{{ asset('assets/js/plugin.js') }}"></script>
