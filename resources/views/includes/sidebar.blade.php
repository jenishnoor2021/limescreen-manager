<div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Menu</li>

            <li>
                <a href="/admin/dashboard" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Dashboard</span>
                </a>
            </li>

            @if(Session::get('user')['role'] == 'Admin')
            <li>
                <a href="/admin/branches" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Branch</span>
                </a>
            </li>
            <li>
                <a href="/admin/package" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Package</span>
                </a>
            </li>
            <li>
                <a href="/admin/users" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Managers</span>
                </a>
            </li>
            @endif

            <li>
                <a href="/admin/customers" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Client</span>
                </a>
            </li>

            <li>
                <a href="/admin/report" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Report</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Sidebar -->
</div>