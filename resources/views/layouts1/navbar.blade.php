<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="/dashboard" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('ars.png') }}" alt="Logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @if(auth()->user()->role === 'Owner')
                <li class="pc-item">
                    <a href="/dashboard" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-home"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/photobooths" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-camera"></i></span>
                        <span class="pc-mtext">Daftar Photobooth</span>
                    </a>
                </li>
                @endif
                <li class="pc-item pc-caption">
                    <label>Pages</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="/markers" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-polaroid"></i></span>
                        <span class="pc-mtext">Upload Foto</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/markers/monthly-photos" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-folder"></i></span>
                        <span class="pc-mtext">Folder Foto</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/markers/upload-mind" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-stack"></i></span>
                        <span class="pc-mtext">Upload Marker</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="https://localhost:5173/" class="pc-link" target="_blank">
                        <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
                        <span class="pc-mtext">Convert Foto</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Use AR JS</label>
                    <i class="ti ti-news"></i>
                </li>
                <li class="pc-item">
                    <a href="/markers/upload-patt" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-stack"></i></span>
                        <span class="pc-mtext">Upload File Patt</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'Owner')
                <li class="pc-item pc-caption">
                    <label>More Menu</label>
                    <i class="ti ti-news"></i>
                </li>
                <li class="pc-item">
                    <a href="/users" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Manajemen User</span>
                    </a>
                </li>
                @endif
                {{-- <li class="pc-item">
                    <a href="../pages/register.html" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                        <span class="pc-mtext">Register</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Other</label>
                    <i class="ti ti-brand-chrome"></i>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-menu"></i></span><span
                            class="pc-mtext">Menu
                            levels</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">Level 2.1</a></li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Level 2.2<span class="pc-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i
                                                data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Level 2.3<span class="pc-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i
                                                data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="pc-item">
                    <a href="../other/sample-page.html" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
                        <span class="pc-mtext">Sample page</span>
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>
