<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- @foreach (getMenus() as $menu)
            @can('read ' . $menu->url)
                @if ($menu->type_menu == 'parent')
                    <li class="menu-item {{ request()->segment(1) == $menu->url ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti {{ $menu->icon }}"></i>
                            <div>{{ $menu->name }}</div>
                        </a>
                        <ul class="menu-sub">
                            @foreach ($menu->subMenus as $submenu)
                                @can('read ' . $submenu->url)
                                    <li
                                        class="menu-item {{ request()->segment(1) == explode('/', $submenu->url)[0] && request()->segment(2) == explode('/', $submenu->url)[1] ? 'active' : '' }}">
                                        <a href="{{ url($submenu->url) }}" class="menu-link">
                                            <div>{{ $submenu->name }}</div>
                                        </a>
                                    </li>
                                @endcan
                            @endforeach
                        </ul>
                    </li>
                @elseif ($menu->type_menu == 'single')
                    <li class="menu-item {{ request()->segment(1) == $menu->url ? 'active' : '' }}">
                        <a href="{{ url($menu->url) }}" class="menu-link">
                            <i class="menu-icon tf-icons ti {{ $menu->icon }}"></i>
                            <div>{{ $menu->name }}</div>
                        </a>
                    </li>
                @endif
            @endcan
        @endforeach --}}

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="index.html" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                fill="#7367F0" />
                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                fill="#7367F0" />
                        </svg>
                    </span>
                    <span class="app-brand-text demo menu-text fw-bold fs-5">{{ config('app.name') }}</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                {{-- Dashboard --}}
                <li class="menu-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-smart-home"></i>
                        <div>Dashboard</div>
                    </a>
                </li>

                {{-- Deposit --}}
                <li class="menu-item {{ request()->is('deposit*') ? 'active' : '' }}">
                    <a href="{{ route('deposit.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-wallet"></i>
                        <div>Deposit</div>
                    </a>
                </li>

                @role('admin')
                    {{-- Admin Menu --}}
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Admin Management</span>
                    </li>
                    <li class="menu-item {{ request()->is('admin/recharge/*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti ti-device-imac-cog"></i>
                            <div>Menu Layanan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('admin/recharge/title') ? 'active' : '' }}">
                                <a href="{{ route('title.index') }}" class="menu-link">
                                    <div>Title</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('admin/recharge/item') ? 'active' : '' }}">
                                <a href="{{ route('item.index') }}" class="menu-link">
                                    <div>Item</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item {{ request()->is('admin/users') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-user-cog"></i>
                            <div>Users</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('payment-method') ? 'active' : '' }}">
                        <a href="{{ route('payment-method.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-cash-banknote"></i>
                            <div>Payment Method</div>
                        </a>
                    </li>

                    {{-- Settings --}}
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Settings</span>
                    </li>
                    <li class="menu-item {{ request()->is('settings/acl/roles') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-user-bolt"></i>
                            <div>Role</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('settings/landingpage/*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti ti-home-cog"></i>
                            <div>Landingpage</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('settings/landingpage/hero') ? 'active' : '' }}">
                                <a href="{{ route('hero.index') }}" class="menu-link">
                                    <div>Hero</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <!-- Misc -->
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text" data-i18n="Misc">Misc</span>
                </li>
                <li class="menu-item">
                    <a href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/" target="_blank"
                        class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div data-i18n="Documentation">Documentation</div>
                    </a>
                </li>
            </ul>
        </aside>
    </ul>
</aside>