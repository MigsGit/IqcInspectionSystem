<aside class="main-sidebar sidebar-dark-navy elevation-4" style="height: 100vh">
{{-- <aside class="main-sidebar sidebar-dark-navy elevation-4" > --}}

    <!-- System title and logo -->
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        {{-- <a href="" class="brand-link text-center"> --}}
        {{-- <img src="{{ asset('public/images/pricon_logo2.png') }}" --}}
        <img src="" class="brand-image img-circle elevation-3" style="opacity: .8">

        <span class="brand-text font-weight-light font-size">
            <h5>PATS PPD-CN171</h5>
        </span>
    </a> <!-- System title and logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- <li class="nav-item has-treeview">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"> </i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> --}}
                {{-- @auth --}}
                    {{-- @if ( in_array(Auth::user()->position, [0,2,5])) --}}
                        <li class="nav-header mt-3"><strong>QUALITY CONTROL</strong></li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-search"> </i>
                                <p> IQC Inspection </p>&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-down"> </i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('iqc_inspection_ts') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon ml-2"> </i>
                                        <p>IQC</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    {{-- @endif --}}
                {{-- @endauth --}}
            </ul>
        </nav>
    </div><!-- Sidebar -->
</aside>
