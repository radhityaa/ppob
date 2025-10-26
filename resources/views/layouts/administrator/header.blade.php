<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-xl-0 d-xl-none me-3">
        <a class="nav-item nav-link me-xl-4 px-0" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav align-items-center ms-auto flex-row">
            <!-- Notification -->
            {{-- <x-notification /> --}}
            <!--/ Notification -->

            {{-- Sisa Saldo --}}
            <li class="nav-item d-flex align-items-center me-3">
                <div class="d-flex align-items-center rounded p-2">
                    <i class="ti ti-wallet ti-sm text-primary me-2"></i>
                    <span class="fw-semibold text-dark" style="font-size: 1rem;">
                        Saldo:&nbsp;
                        <span class="badge bg-success rounded-pill ms-1" style="font-size: 0.95em;">
                            Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}
                        </span>
                    </span>
                </div>
            </li>
            <!--/ Sisa Saldo -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/logo.png') }}" alt class="rounded-circle h-auto" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex">
                                <div class="me-3 flex-shrink-0">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/logo.png') }}" alt
                                            class="rounded-circle h-auto" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->getRoleNames()[0] }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.account', Auth::user()->username) }}">
                            <i class="ti ti-user-check ti-sm me-2"></i>
                            <span class="align-middle">Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout ti-sm me-2"></i>
                            <span class="align-middle">LogOut</span>
                        </a>
                        <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
            aria-label="Search..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
