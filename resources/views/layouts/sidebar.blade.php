<div class="sidebar-custom" id="app-sidebar">

    <div class="sidebar-inner">

        {{-- Logo --}}
        <div class="sidebar-logo">

            <a href="{{ route('dashboard') }}" class="sidebar-logo-link">

                <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="sidebar-company-logo">

            </a>

        </div>


        {{-- Menu --}}
        <div class="navbar-nav sidebar-nav w-100">

            {{-- Dashboard --}}
            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-dashboard"></i>
                    </span>

                    <span class="nav-link-title">
                        Dashboard
                    </span>

                </a>
            </div>


            {{-- Master Sarana Inventaris --}}
            <div class="nav-item">
                <a href="{{ route('master-sarana-inventaris.index') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('master-sarana-inventaris.*') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-category"></i>
                    </span>

                    <span class="nav-link-title">
                        Master Sarana Inventaris
                    </span>

                </a>
            </div>


            {{-- Sarana Inventaris --}}
            <div class="nav-item">
                <a href="{{ route('inventories.index') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('inventories.*') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-package"></i>
                    </span>

                    <span class="nav-link-title">
                        Sarana Inventaris
                    </span>

                </a>
            </div>


            {{-- Lokasi --}}
            <div class="nav-item">
                <a href="{{ route('locations.index') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-map-pin"></i>
                    </span>

                    <span class="nav-link-title">
                        Lokasi
                    </span>

                </a>
            </div>


            {{-- Penanggung Jawab --}}
            <div class="nav-item">
                <a href="{{ route('responsibles.index') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('responsibles.*') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-users"></i>
                    </span>

                    <span class="nav-link-title">
                        Penanggung Jawab
                    </span>

                </a>
            </div>


            {{-- Laporan --}}
            <div class="nav-item">
                <a href="{{ route('reports.index') }}"
                    class="nav-link sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                    <span class="sidebar-icon">
                        <i class="ti ti-report-analytics"></i>
                    </span>

                    <span class="nav-link-title">
                        Laporan
                    </span>

                </a>
            </div>

            {{-- Akun --}}
            @auth

                @if (auth()->user()->role === 'admin')
                    <div class="nav-item">

                        <a href="{{ route('users.index') }}"
                            class="nav-link sidebar-link
                    {{ request()->routeIs('users.*') ? 'active' : '' }}">

                            <span class="sidebar-icon">
                                <i class="ti ti-users"></i>
                            </span>

                            <span class="nav-link-title">
                                Akun
                            </span>

                        </a>

                    </div>

                    {{-- Backup & Restore --}}
                    <div class="nav-item">

                        <a href="{{ route('backups.index') }}"
                            class="nav-link sidebar-link
                {{ request()->routeIs('backups.*') ? 'active' : '' }}">

                            <span class="sidebar-icon">
                                <i class="ti ti-database"></i>
                            </span>

                            <span class="nav-link-title">
                                Backup & Restore
                            </span>

                        </a>

                    </div>
                @endif

            @endauth

        </div>

    </div>

</div>
