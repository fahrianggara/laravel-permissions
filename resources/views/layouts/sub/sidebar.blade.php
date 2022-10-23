
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('dashboard') }}" class="brand-link" style="text-align: left;">
        <img src="{{ asset('assets/dashboard/img/logo.webp') }}" alt="{{ config('app.name') }} Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text ml-1">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                @php
                    if (file_exists('assets/dashboard/img/users/' . Auth::user()->photo)) {
                        $avatar = asset('assets/dashboard/img/users/' . Auth::user()->photo);
                    } else {
                        $avatar = asset('assets/dashboard/img/avatar.png');
                    }
                @endphp
                <img id="sideProfile" src="{{ $avatar }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a id="userName" href="{{ route('profile.index') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted"> {{ Auth::user()->roles->first()->name }}</small>
            </div>
        </div>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-header">DASHBOARD</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ setActive('dashboard') }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}" class="nav-link {{ setActive(['profile.index']) }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Notifications
                        </p>
                    </a>
                </li> --}}

                @if (auth()->user()->can('view user') || auth()->user()->can('view role') || auth()->user()->can('view permission'))
                    <li class="nav-header">USER MANAGEMENT</li>

                    @can('view role')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ setActive(['roles.index']) }}">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>
                                    Roles
                                </p>
                            </a>
                        </li>
                    @endcan

                    @can('view permission')
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ setActive(['permissions.index', 'label-permissions.index']) }}">
                                <i class="nav-icon fas fa-sliders-h"></i>
                                <p>
                                    Permission Settings
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview"
                                style="{{ d_block(['permissions.index', 'label-permissions.index']) }}">

                                <li class="nav-item">
                                    <a href="{{ route('label-permissions.index') }}"
                                        class="nav-link {{ setActive(['label-permissions.index']) }}">
                                        <i class="fas fa-tag nav-icon"></i>
                                        <p>Permission Labels</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('permissions.index') }}"
                                        class="nav-link {{ setActive(['permissions.index']) }}">
                                        <i class="nav-icon fas fa-user-shield"></i>
                                        <p>
                                            Permissions
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endcan

                    @can('view user')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ setActive(['users.index']) }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif


                <li class="nav-header">LOGOUT</li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="modal" data-target="#logModal">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>

                {{-- MODAL LOGOUT --}}
                <div class="modal fade" id="logModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                Do you want to logout?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{ route('logout') }}" type="button" class="btn btn-danger"
                                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </ul>
        </nav>

    </div>
</aside>
