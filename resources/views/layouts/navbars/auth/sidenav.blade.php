<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}">
            <img src="{{ asset('/img/iotlab.jpg') }}" class="navbar-brand-img h-100 ms-1" alt="main_logo">
            <span class="ms-1 font-weight-bold">Smart EnMS</span>
            <small class="font-weight-light"> FT UNS</small>
            <!-- <small class="font-weight-light">by Trofis.tech</small> -->
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}"
                    href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Electricity</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'energy') == true ? 'active' : '' }}"
                    href="{{ route('energy-monitor') }}">
                    {{-- <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="#energyDropdown"> --}}
                        <div
                            class=" icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-plug-circle-bolt" style="color: #e0de4f;"></i>
                        </div>
                        <span class="nav-link-text ms-1">Electrical Energy</span>
                    </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'nre') == true ? 'active' : '' }}"
                    href="{{ route('nreIndex') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-leaf" style="color: #37c414"></i>
                    </div>
                    <span class="nav-link-text ms-1">Renewable Energy</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'standar-ike') == true ? 'active' : '' }}"
                    href="{{ route('standar-ike') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-scale-balanced" style="color: #1d51b9;"></i>
                    </div>
                    <span class="nav-link-text ms-1">IKE Standard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Water</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'water') == true ? 'active' : '' }}"
                    href="{{ route('water-monitor') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-water" style="color: #3789c8;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Water Management</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reports</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'enms-report') == true ? 'active' : '' }}"
                    href="{{ route('enms-report') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-regular fa-clipboard" style="color: #830707;"></i>
                    </div>
                    <span class="nav-link-text ms-1">EnMS Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('subdatas.index') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <span class="nav-link-text ms-1">Setting</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Environmental</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'security') == true ? 'active' : '' }}"
                    href="{{ url('security-camera') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-shield-halved" style="color: #1671c0;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Security</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'envi') == true ? 'active' : '' }}"
                    href="{{ route('envi-sense') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-lightbulb" style="color: #fd7e14"></i>
                    </div>
                    <span class="nav-link-text ms-1">Environment</span>
                </a>
            </li>

            <li class="nav-item mt-3 d-flex align-items-center">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account Info</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}"
                    href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user-management') == true ? 'active' : '' }}"
                    href="{{ route('page', ['page' => 'user-management']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">List of Access</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Config Pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile-static' ? 'active' : '' }}"
                    href="{{ route('profile-static') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-list-check text-danger"></i>
                    </div>
                    <span class="nav-link-text ms-1">List of Devices</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="{{ route('sign-up-static') }}">
                    <div
                        class="icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-microchip" style="color: #596CFF"></i>
                    </div>
                    <span class="nav-link-text ms-1">Integrated System</span>
                </a>
            </li>
            <!-- <li class="nav-item text-center">
                <a href="/docs/bootstrap/overview/argon-dashboard/index.html" target="_blank"
                    class="btn btn-dark btn-sm w-75 mb-3">Documentation</a>
            </li> -->
        </ul>
    </div>
</aside>