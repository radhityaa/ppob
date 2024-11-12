@props(['username'])

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile/*/account') ? 'active' : '' }}"
                    href="{{ route('profile.account', $username) }}"><i class="ti-xs ti ti-users me-1"></i> Akun</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile/*/shop') ? 'active' : '' }}"
                    href="{{ route('profile.shop', $username) }}"><i class="ti-xs ti ti-lock me-1"></i>
                    Toko</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile/*/security') ? 'active' : '' }}"
                    href="{{ route('profile.security', $username) }}"><i class="ti-xs ti ti-lock me-1"></i>
                    Keamanan</a>
            </li>
            @role('member|admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('commingsoon') }}"><i class="ti-xs ti ti-user-up me-1"></i>
                        Upgrade</a>
                </li>
            @endrole
        </ul>
        {{ $slot }}
    </div>
</div>
