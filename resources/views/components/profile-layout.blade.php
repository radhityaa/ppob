@props(['username'])

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile/*/edit') ? 'active' : '' }}"
                    href="{{ route('profile.edit', $username) }}"><i class="ti-xs ti ti-users me-1"></i> Akun</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile/*/security') ? 'active' : '' }}"
                    href="{{ route('profile.security', $username) }}"><i class="ti-xs ti ti-lock me-1"></i>
                    Keamanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-billing.html"><i
                        class="ti-xs ti ti-file-description me-1"></i> Paket</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-notifications.html"><i
                        class="ti-xs ti ti-bell me-1"></i> Notifikasi</a>
            </li>
        </ul>
        {{ $slot }}
    </div>
</div>
