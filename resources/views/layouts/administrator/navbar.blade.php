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

        {{-- Mutation --}}
        <li class="menu-item {{ request()->is('mutations*') ? 'active' : '' }}">
            <a href="{{ route('mutations.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-moneybag"></i>
                <div>Mutasi Saldo</div>
            </a>
        </li>

        {{-- History --}}
        <li class="menu-item {{ request()->is('history/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-history"></i>
                <div>Riwayat Transaksi</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('history/prabayar') ? 'active' : '' }}">
                    <a href="{{ route('history.prabayar') }}" class="menu-link">
                        <div>Prabayar</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Pascabayar (Commingsoon)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('history/premium-account*') ? 'active' : '' }}">
                    <a href="{{ route('history.premium-account') }}" class="menu-link">
                        <div>Akun Premium</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Product --}}
        <li class="menu-item {{ request()->is('product/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-list"></i>
                <div>Daftar Harga</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('product/prabayar') ? 'active' : '' }}">
                    <a href="{{ route('prabayar.index') }}" class="menu-link">
                        <div>Prabayar</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('product/pascabayar') ? 'active open' : '' }}">
                    <a href="{{ route('pascabayar.index') }}" class="menu-link">
                        <div>Pascabayar</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('product/premium-account') ? 'active' : '' }}">
                    <a href="{{ route('premium-account.index') }}" class="menu-link">
                        <div>Akun Premium</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Level Upgrade --}}
        <li class="menu-item {{ request()->is('level-upgrade*') ? 'active' : '' }}">
            <a href="{{ route('level-upgrade.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-trending-up"></i>
                <div>Upgrade Level</div>
            </a>
        </li>

        {{-- Information --}}
        <li class="menu-item {{ request()->is('information*') ? 'active' : '' }}">
            <a href="{{ route('information.all') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-news"></i>
                <div>Pusat Informasi</div>
            </a>
        </li>

        {{-- Ticket Support --}}
        <li class="menu-item {{ request()->is('ticket*') ? 'active' : '' }}">
            <a href="{{ route('ticket.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-ticket"></i>
                <div>Tiket Support</div>
            </a>
        </li>

        {{-- Report --}}
        <li class="menu-item {{ request()->is('report*') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                <div>Laporan Saya</div>
            </a>
        </li>

        {{-- Reseller Menu --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu Reseller</span>
        </li>
        @role('reseller|admin')
            {{-- Transfer Saldo --}}
            <li class="menu-item {{ request()->is('transfer*') ? 'active' : '' }}">
                <a href="{{ route('transfer.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-arrows-transfer-up"></i>
                    <div>Transfer Saldo</div>
                </a>
            </li>

            {{-- Voucher --}}
            {{-- <li class="menu-item {{ request()->is('voucher*') ? 'active' : '' }}">
                <a href="{{ route('voucher.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-receipt"></i>
                    <div>Voucher Management</div>
                </a>
            </li> --}}

            {{-- Register Agen --}}
            <li class="menu-item {{ request()->is('agen*') ? 'active' : '' }}">
                <a href="{{ route('agen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>Daftar Agen</div>
                </a>
            </li>

            {{-- Profit --}}
            <li class="menu-item {{ request()->is('profits*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-brand-cashapp"></i>
                    <div>Profit</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('profits/history*') ? 'active' : '' }}">
                        <a href="{{ route('profits.index') }}" class="menu-link">
                            <div>Riwayat Profit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('profits/withdrawal*') ? 'active' : '' }}">
                        <a href="{{ route('profits.withdrawal.index') }}" class="menu-link">
                            <div>Penarikan</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endrole

        @role('admin')
            {{-- Admin Menu --}}

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Admin Management</span>
            </li>
            {{-- Admin Dashboard --}}
            <li class="menu-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-dashboard"></i>
                    <div>Admin Dashboard</div>
                </a>
            </li>

            {{-- Admin Report --}}
            <li class="menu-item {{ request()->is('admin/report*') ? 'active' : '' }}">
                <a href="{{ route('admin.report.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                    <div>Report Admin</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/recharge/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-device-imac-cog"></i>
                    <div>Kategori Layanan</div>
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
            <li class="menu-item {{ request()->is('admin/information*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-news"></i>
                    <div>Kelola Informasi</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('admin/information') ? 'active' : '' }}">
                        <a href="{{ route('information.index') }}" class="menu-link">
                            <div>Informasi</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('admin/information/category') ? 'active' : '' }}">
                        <a href="{{ route('information.category.index') }}" class="menu-link">
                            <div>Kategori</div>
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
            <li class="menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-settings-cog"></i>
                    <div>Setting App</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/landingpage/settings/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-home-cog"></i>
                    <div>Landingpage</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('admin/landingpage/settings*') ? 'active' : '' }}">
                        <a href="{{ route('hero.index') }}" class="menu-link">
                            <div>Hero</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- ACL --}}
            {{-- <li class="menu-item {{ request()->is('settings/acl/roles') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-user-bolt"></i>
                    <div>Role</div>
                </a>
            </li> --}}
        @endrole

        {{-- Whatsapp Gateway --}}
        {{-- <li class="menu-item {{ request()->is('whatsapp*') ? 'active' : '' }}">
            <a href="{{ route('whatsapp.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-brand-whatsapp"></i>
                <div>Whatsapp Gateway</div>
            </a>
        </li> --}}

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
