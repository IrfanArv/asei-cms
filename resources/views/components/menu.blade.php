<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand ">
        <a href="{{ ENV('APP_URL') . '/dashboard' }}" class="app-brand-link">
            <img id="logo-image" src="{{ asset('assets/img/logo.svg') }}" alt="auth-login-cover"
                class="img-fluid logo-main-dash" />
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ Request::segment(2) === null ? 'active' : null }}">
            <a href="{{ ENV('APP_URL') . '/dashboard' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <li class="menu-item {{ Request::segment(2) === 'web-pages' ? 'active' : null }}">
            <a href="{{ ENV('APP_URL') . '/dashboard/web-pages' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-app-window"></i>
                <div>Halaman</div>
            </a>
        </li>
        <li
            class="menu-item {{ Request::segment(2) === 'article-news' ? 'active open' : null }} {{ Request::segment(2) === 'news-category' ? 'active open' : null }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-news"></i>
                <div>Berita</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::segment(2) === 'article-news' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/article-news' }}" class="menu-link">
                        <div>Berita</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'news-category' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/news-category' }}" class="menu-link">
                        <div>Kategori</div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- <li
            class="menu-item {{ Request::segment(2) === 'insurance-products' ? 'active open' : null }} {{ Request::segment(2) === 'insurance-category' ? 'active open' : null }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shield-check"></i>
                <div>Insurance Products</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::segment(2) === 'insurance-products' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/insurance-products' }}" class="menu-link">
                        <div>Products</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'insurance-category' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/insurance-category' }}" class="menu-link">
                        <div>Categories</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <li class="menu-item {{ Request::segment(2) === 'insurance' ? 'active' : null }}">
            <a href="{{ ENV('APP_URL') . '/dashboard/insurance' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shield-check"></i>
                <div>Produk Asuransi</div>
            </a>
        </li>

        <li class="menu-item {{ Request::segment(2) === 'corporate-social-responsibility' ? 'active' : null }}">
            <a href="{{ ENV('APP_URL') . '/dashboard/corporate-social-responsibility' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-heart-handshake"></i>
                <div>Tanggung jawab sosial</div>
            </a>
        </li>

        {{-- <li class="menu-item">
            <a href="{{ ENV('APP_URL') . '/dashboard/networks' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-certificate"></i>
                <div>Penghargaan & Link</div>
            </a>
        </li> --}}

        <li class="menu-item {{ Request::segment(2) === 'networks' ? 'active' : null }}">
            <a href="{{ ENV('APP_URL') . '/dashboard/networks' }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layers-linked"></i>
                <div>Jaringan</div>
            </a>
        </li>

        {{-- <li class="menu-item {{ Request::segment(2) === 'term-of-aggrement' ? 'active open' : null }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-certificate-2"></i>
                <div>Term of Aggrement</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::segment(2) === 'term-of-aggrement' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/term-of-aggrement' }}" class="menu-link">
                        <div>Term of Use</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'term-of-aggrement' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/term-of-aggrement' }}" class="menu-link">
                        <div>Privacy Policy</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'term-of-aggrement' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/term-of-aggrement' }}" class="menu-link">
                        <div>F.A.Q</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'term-of-aggrement' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/term-of-aggrement' }}" class="menu-link">
                        <div>Whistleblowing</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <li
            class="menu-item {{ Request::segment(2) === 'roles' ? 'active open' : null }} {{ Request::segment(2) === 'admin-users' ? 'active open' : null }} {{ Request::segment(2) === 'web-settings' ? 'active open' : null }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>Pengaturan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::segment(2) === 'web-settings' ? 'active' : null }}">
                    <a href="{{ ENV('APP_URL') . '/dashboard/web-settings' }}" class="menu-link">
                        <div>Web Setting</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(2) === 'admin-users' ? 'active open' : null }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div>Admin Users</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ Request::segment(2) === 'admin-users' ? 'active open' : null }}">
                            <a href="{{ ENV('APP_URL') . '/dashboard/admin-users' }}" class="menu-link">
                                <div>Users</div>
                            </a>
                        </li>
                        {{-- <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link">
                                <div>Roles</div>
                            </a>
                        </li> --}}
                    </ul>
                </li>
            </ul>
        </li>


    </ul>
</aside>
