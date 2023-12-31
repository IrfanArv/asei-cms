<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>



    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class="ti ti-md"></i>
                </a>
            </li>
            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="{{ env('WORDPRESS_URL') }}" target="_blank"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Visit Website">
                    <i class="ti ti-device-desktop"></i>
                </a>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->image)
                            <img id="preview" class="h-auto rounded-circle" height="40" width="40"
                                src="{{ '/img/user/' . Auth::user()->image }}" alt="{{ Auth::user()->name }}">
                        @else
                            <img id="preview"
                                src="https://api.dicebear.com/6.x/notionists/svg?seed={{ Auth::user()->name }}&backgroundColor=005e9d&&lips=variant07,variant22"
                                class="h-auto rounded-circle" height="40" width="40">
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->image)
                                            <img id="preview" class="h-auto rounded-circle" height="40"
                                                width="40" src="{{ '/img/user/' . Auth::user()->image }}"
                                                alt="{{ Auth::user()->name }}">
                                        @else
                                            <img id="preview"
                                                src="https://api.dicebear.com/6.x/notionists/svg?seed={{ Auth::user()->name }}&backgroundColor=005e9d&lips=variant07,variant22"
                                                class="h-auto rounded-circle" height="40" width="40">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li> --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">Log Out</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('POST')
                            </form>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
