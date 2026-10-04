<div class="navbar-custom">

    <div class="container-xl d-flex align-items-center">

        {{-- ========================================
            JUDUL SISTEM
        ======================================== --}}
        <div class="navbar-title">

            <div class="navbar-title-main">
                Sistem Sarana Inventaris
            </div>

        </div>


        {{-- ========================================
            BAGIAN AKUN
        ======================================== --}}
        <div class="ms-auto">


            {{-- ========================================
                GUEST
            ======================================== --}}
            @guest

                <div class="dropdown">

                    <button class="navbar-user navbar-user-button" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <div class="navbar-user-avatar navbar-user-avatar-guest">

                            <i class="ti ti-user"></i>

                        </div>


                        <div class="navbar-user-info">

                            <div class="navbar-user-name">
                                Guest
                            </div>

                            <div class="navbar-user-role">
                                Pengunjung
                            </div>

                        </div>


                        <i class="ti ti-chevron-down navbar-user-chevron"></i>

                    </button>


                    <div class="dropdown-menu dropdown-menu-end navbar-account-dropdown">

                        <div class="navbar-account-header">

                            <div class="navbar-account-avatar navbar-account-avatar-guest">

                                <i class="ti ti-user"></i>

                            </div>


                            <div>

                                <div class="navbar-account-name">
                                    Guest
                                </div>

                                <div class="navbar-account-role">
                                    Pengunjung
                                </div>

                            </div>

                        </div>


                        <div class="dropdown-divider"></div>


                        <a href="{{ route('login') }}" class="dropdown-item navbar-login-button">

                            <i class="ti ti-login me-2"></i>

                            Masuk

                        </a>

                    </div>

                </div>

            @endguest



            {{-- ========================================
                USER SUDAH LOGIN
            ======================================== --}}
            @auth

                @php

                    $user = auth()->user();

                    $initial = strtoupper(mb_substr($user->username, 0, 1));

                    /*
                    |----------------------------------------
                    | NAMA ROLE / PENANGGUNG JAWAB
                    |----------------------------------------
                    |
                    | Admin:
                    | Administrator
                    |
                    | Responsible:
                    | Nama penanggung jawab sebenarnya
                    | Contoh: HCE, IT, General Affair
                    |
                    */

                    $roleName =
                        $user->role === 'admin'
                            ? 'Administrator'
                            : $user->responsible?->nama_penanggung_jawab ?? 'Penanggung Jawab';

                @endphp


                <div class="dropdown">

                    <button class="navbar-user navbar-user-button" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">

                        {{-- Avatar --}}
                        <div class="navbar-user-avatar">

                            {{ $initial }}

                        </div>


                        {{-- Informasi User --}}
                        <div class="navbar-user-info">

                            <div class="navbar-user-name">

                                {{ $user->username }}

                            </div>


                            <div class="navbar-user-role">

                                {{ $roleName }}

                            </div>

                        </div>


                        <i class="ti ti-chevron-down navbar-user-chevron"></i>

                    </button>



                    {{-- ========================================
                        DROPDOWN AKUN
                    ======================================== --}}
                    <div class="dropdown-menu dropdown-menu-end navbar-account-dropdown">


                        {{-- HEADER DROPDOWN --}}
                        <div class="navbar-account-header">

                            <div class="navbar-account-avatar">

                                {{ $initial }}

                            </div>


                            <div>

                                <div class="navbar-account-name">

                                    {{ $user->username }}

                                </div>


                                <div class="navbar-account-role">

                                    {{ $roleName }}

                                </div>

                            </div>

                        </div>


                        <div class="dropdown-divider"></div>


                        {{-- KELUAR --}}
                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="dropdown-item navbar-logout-button">

                                <i class="ti ti-logout me-2"></i>

                                Keluar

                            </button>

                        </form>


                    </div>

                </div>

            @endauth


        </div>

    </div>

</div>
