<aside class="main-sidebar sidebar-dark-navy elevation-4" style="height: 100vh">
{{-- <aside class="main-sidebar sidebar-dark-navy elevation-4" > --}}

    <!-- System title and logo -->
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        {{-- <a href="" class="brand-link text-center"> --}}
        {{-- <img src="{{ asset('public/images/pricon_logo2.png') }}" --}}
        <img src="" class="brand-image img-circle elevation-3" style="opacity: .8">

        <span class="brand-text font-weight-light font-size">
            <h5>IQC Inspection System</h5>
        </span>
    </a> <!-- System title and logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ url('../RapidX') }}" class="nav-link">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>Return to RapidX</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"> </i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- @if ( in_array(Auth::user()->position, [0,2,5])) --}}
                    <li class="nav-header mt-3"><strong>QUALITY CONTROL</strong></li>
                    <li class="nav-item nav-item-ts d-none">
                        <a href="{{ route('ts_iqc_inspection') }}" class="nav-link">
                            <i class="far fa-circle nav-icon ml-2"> </i>
                            <p>TS IQC</p>
                        </a>
                    </li>
                    <li class="nav-item nav-item-cn d-none">
                        <a href="{{ route('cn_iqc_inspection') }}" class="nav-link">
                            <i class="far fa-circle nav-icon ml-2"> </i>
                            <p>CN IQC</p>
                        </a>
                    </li>
                    <li class="nav-item nav-item-ppd d-none">
                        <a href="{{ route('ppd_iqc_inspection') }}" class="nav-link">
                            <i class="far fa-circle nav-icon ml-2"> </i>
                            <p>PPD IQC</p>
                        </a>
                    </li>
                    <li class="nav-item nav-item-yf d-none">
                        <a href="{{ route('yf_iqc_inspection') }}" class="nav-link">
                            <i class="far fa-circle nav-icon ml-2"> </i>
                            <p>YF IQC</p>
                        </a>
                    </li>
                {{-- @endif --}}
                {{-- @if ( in_array(Auth::user()->position, [0,2,5])) --}}
                    <li class="nav-header mt-3"><strong>Settings</strong></li>
                    <li class="nav-item">
                        <a href="{{ route('dropdown_maintenance') }}" class="nav-link">
                            <i class="far fa-circle nav-icon ml-2"> </i>
                            <p>Dropdown Maintenance</p>
                        </a>
                    </li>
                {{-- @endif --}}
            </ul>
        </nav>
    </div><!-- Sidebar -->
</aside>
