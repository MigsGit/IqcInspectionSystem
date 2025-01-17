<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link burgerMenu" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="" class="nav-link">IQC Inspection System</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <div class="navbar-nav">
        <li class="nav-item dropdown">
            <button class="btn theme-color" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                        // echo session("rapidx_name");
                        // echo session("rapidx_department_id");
                        echo $_SESSION["rapidx_name"];
                @endphp
                {{-- {{ ucwords($_SESSION["session_firstname"]) .' '. ucwords($_SESSION["session_lastname"]) }}&nbsp;<i class="far fa-user"></i> --}}
                {{-- {{ Auth::user()->firstname." ".Auth::user()->lastname}} --}}
                {{-- {{ session('rapidx_name') }}
                 --}}
                {{-- MIGZ --}}
            </button>
        </li>
    </div>
</nav>

<div class="modal fade" id="modalLogout">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Logout</h4>
                <button type="button" class="btn-close" style="margin-top: 3px;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSignOut">
                @csrf
                <div class="modal-body">
                    <p id="lblSignOut" class="mt-2 theme-color">Are you sure to logout your account?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default theme-color" data-bs-dismiss="modal">No</button>
                    <button type="button" id="btnLogout" class="btn theme-color-bg text-white"><i id="iconLogout" class="fa fa-check"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

