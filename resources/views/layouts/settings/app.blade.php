@extends('layouts.administrator.app')

@section('content')
    <!-- Navigation -->
    <div class="col-12 col-lg-4">
        <div class="d-flex justify-content-between flex-column mb-md-0 mb-3">
            <ul class="nav nav-align-left nav-pills flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.index') }}">
                        <i class="ti ti-building-store me-2"></i>
                        <span class="align-middle">Margin Product</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->is('admin/settings/profit') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.profit.index') }}">
                        <i class="ti ti-brand-cashapp me-2"></i>
                        <span class="align-middle">Profit Reseller</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings/notification') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.notification') }}">
                        <i class="ti ti-bell-ringing me-2"></i>
                        <span class="align-middle">Notifications</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->is('admin/settings/message-template*') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.message-template.template.index') }}">
                        <i class="ti ti-send me-2"></i>
                        <span class="align-middle">Notification Template</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->is('admin/settings/provider/setting*') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.provider.setting') }}">
                        <i class="ti ti-home-cog me-2"></i>
                        <span class="align-middle">Setting Provider</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link py-2" href="javascript:;">
                        <i class="ti ti-home-cog me-2"></i>
                        <span class="align-middle">Change Provider (Comming Soon)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings/information-deposit') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.informationDeposit') }}">
                        <i class="ti ti-list-numbers me-2"></i>
                        <span class="align-middle">Informasi Deposit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings/env') ? 'active' : '' }} py-2"
                        href="{{ route('admin.settings.env.show') }}">
                        <i class="ti ti-settings me-2"></i>
                        <span class="align-middle">ENV</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Navigation -->

    <!-- Options -->
    <div class="col-12 col-lg-8 pt-lg-0 pt-4">
        <div class="tab-content p-0">
            @yield('content-tab')
        </div>
    </div>
    <!-- /Options-->
@endsection
