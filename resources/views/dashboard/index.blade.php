@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <div class="page-header mb-4">

            <h2 class="page-title">

                Dashboard

            </h2>

        </div>

        <div class="row row-cards">

            {{-- SARANA INVENTARIS --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card dashboard-stat-card">

                    <div class="card-body">

                        <div class="dashboard-stat-header">

                            <div class="dashboard-stat-icon">

                                <i class="ti ti-category"></i>

                            </div>

                            <div class="dashboard-stat-content">

                                <div class="dashboard-stat-title">
                                    Sarana Inventaris
                                </div>

                                <div class="dashboard-stat-value">
                                    {{ $totalMasterSaranaInventaris }}
                                </div>

                            </div>

                        </div>

                        <div class="dashboard-stat-description">

                            Jenis sarana yang terdaftar

                        </div>

                    </div>

                </div>

            </div>


            {{-- TOTAL INVENTARIS --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card dashboard-stat-card">

                    <div class="card-body">

                        <div class="dashboard-stat-header">

                            <div class="dashboard-stat-icon">

                                <i class="ti ti-package"></i>

                            </div>

                            <div class="dashboard-stat-content">

                                <div class="dashboard-stat-title">
                                    Total Inventaris
                                </div>

                                <div class="dashboard-stat-value">
                                    {{ $totalInventories }}
                                </div>

                            </div>

                        </div>

                        <div class="dashboard-stat-description">

                            Total unit inventaris

                        </div>

                    </div>

                </div>

            </div>


            {{-- LOKASI --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card dashboard-stat-card">

                    <div class="card-body">

                        <div class="dashboard-stat-header">

                            <div class="dashboard-stat-icon">

                                <i class="ti ti-map-pin"></i>

                            </div>

                            <div class="dashboard-stat-content">

                                <div class="dashboard-stat-title">
                                    Lokasi
                                </div>

                                <div class="dashboard-stat-value">
                                    {{ $totalLocations }}
                                </div>

                            </div>

                        </div>

                        <div class="dashboard-stat-description">

                            Lokasi yang terdaftar

                        </div>

                    </div>

                </div>

            </div>


            {{-- PENANGGUNG JAWAB --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card dashboard-stat-card">

                    <div class="card-body">

                        <div class="dashboard-stat-header">

                            <div class="dashboard-stat-icon">

                                <i class="ti ti-users"></i>

                            </div>

                            <div class="dashboard-stat-content">

                                <div class="dashboard-stat-title">
                                    Penanggung Jawab
                                </div>

                                <div class="dashboard-stat-value">
                                    {{ $totalResponsibles }}
                                </div>

                            </div>

                        </div>

                        <div class="dashboard-stat-description">

                            Penanggung jawab terdaftar

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
