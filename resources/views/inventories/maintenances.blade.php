@extends('layouts.app')

@section('content')

    @php

        $user = auth()->user();

        $isAdmin = $user && $user->role === 'admin';

        $canManageHistory =
            $isAdmin || ($user && $user->responsible_id && $inventory->responsible_id === $user->responsible_id);
    @endphp

    <div class="container-xl">

        <div class="page-header d-print-none mb-3">

            <div class="row align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Riwayat Perawatan
                    </h2>

                </div>

                <div class="col-auto">

                    @if ($canManageHistory)
                        <a href="{{ route('maintenances.create', $inventory) }}" class="btn btn-primary">

                            Tambah Perawatan

                        </a>
                    @endif

                    <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-secondary">

                        Kembali

                    </a>

                </div>

            </div>

        </div>

        <div class="card mb-4">

            <div class="card-header">
                <h3 class="card-title">
                    Informasi Sarana Inventaris
                </h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="250">Nomor Inventaris</th>
                        <td>{{ $inventory->nomor_inventaris }}</td>
                    </tr>

                    <tr>
                        <th>Sarana Inventaris</th>
                        <td>
                            {{ $inventory->masterSaranaInventaris->nama_sarana_inventaris }}
                        </td>
                    </tr>

                    <tr>
                        <th>User</th>
                        <td>{{ $inventory->user ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $inventory->location->nama_lokasi }}</td>
                    </tr>

                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>
                            {{ $inventory->responsible->nama_penanggung_jawab }}
                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{{ $inventory->status ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Perolehan</th>
                        <td>
                            {{ $inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Foto</th>
                        <td>
                            @if ($inventory->foto)
                                <img src="{{ asset('storage/' . $inventory->foto) }}" width="70" class="img-thumbnail">
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Spesifikasi</th>
                        <td>{{ $inventory->spesifikasi ?: '-' }}</td>
                    </tr>

                </table>

            </div>

        </div>


        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Daftar Riwayat Perawatan
                </h3>


                <div class="card-actions">

                    <form method="GET" action="{{ route('inventories.maintenances', $inventory) }}"
                        class="d-flex gap-2 align-items-center">

                        <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">


                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>


                        @if (request('bulan'))
                            <a href="{{ route('inventories.maintenances', $inventory) }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis Perawatan</th>
                            <th>Pelaksana</th>
                            <th>Perawatan Selanjutnya</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($maintenances as $maintenance)
                            <tr>

                                <td>
                                    {{ $loop->iteration + ($maintenances->firstItem() - 1) }}
                                </td>

                                <td>
                                    {{ $maintenance->tanggal_perawatan?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    {{ $maintenance->jenis_perawatan }}
                                </td>

                                <td>
                                    {{ $maintenance->pelaksana }}
                                </td>

                                <td>
                                    {{ $maintenance->tanggal_perawatan_selanjutnya?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    @if ($canManageHistory)
                                        <a href="{{ route('maintenances.edit', $maintenance) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                            </svg>

                                        </a>
                                    @endif

                                    @if ($isAdmin)
                                        <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Hapus data perawatan?')"
                                                class="btn btn-danger btn-sm btn-icon" title="Hapus" aria-label="Hapus">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                    <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />

                                                </svg>

                                            </button>

                                        </form>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-4">

                                    Belum ada riwayat perawatan.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            @if ($maintenances->hasPages())
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="pagination-wrapper">

                            <div class="pagination-group">

                                <div class="pagination-info">

                                    Showing

                                    <strong>{{ $maintenances->firstItem() }}</strong>

                                    to

                                    <strong>{{ $maintenances->lastItem() }}</strong>

                                    of

                                    <strong>{{ $maintenances->total() }}</strong>

                                    results

                                </div>


                                <div class="pagination-nav">

                                    {{-- SEBELUMNYA --}}
                                    @if ($maintenances->onFirstPage())
                                        <span class="pagination-btn disabled">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $maintenances->previousPageUrl() }}" class="pagination-btn">

                                            ‹

                                        </a>
                                    @endif


                                    {{-- NOMOR HALAMAN --}}
                                    @php
                                        $currentPage = $maintenances->currentPage();
                                        $lastPage = $maintenances->lastPage();

                                        $pages = [];

                                        // Selalu tampilkan halaman pertama
                                        $pages[] = 1;

                                        // Tampilkan halaman sekitar halaman aktif
                                        for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                                            if ($i > 1 && $i < $lastPage) {
                                                $pages[] = $i;
                                            }
                                        }

                                        // Selalu tampilkan halaman terakhir
                                        if ($lastPage > 1) {
                                            $pages[] = $lastPage;
                                        }

                                        // Hapus duplikat
                                        $pages = array_unique($pages);

                                        // Urutkan
                                        sort($pages);

                                        $previousPage = null;
                                    @endphp


                                    @foreach ($pages as $page)
                                        {{-- TITIK-TITIK JIKA ADA HALAMAN YANG DILEWATI --}}
                                        @if ($previousPage !== null && $page > $previousPage + 1)
                                            <span class="pagination-dots">
                                                ...
                                            </span>
                                        @endif


                                        {{-- NOMOR HALAMAN --}}
                                        @if ($page == $currentPage)
                                            <span class="pagination-btn active">

                                                {{ $page }}

                                            </span>
                                        @else
                                            <a href="{{ $maintenances->url($page) }}" class="pagination-btn">

                                                {{ $page }}

                                            </a>
                                        @endif


                                        @php
                                            $previousPage = $page;
                                        @endphp
                                    @endforeach


                                    {{-- SELANJUTNYA --}}
                                    @if ($maintenances->hasMorePages())
                                        <a href="{{ $maintenances->nextPageUrl() }}" class="pagination-btn">

                                            ›

                                        </a>
                                    @else
                                        <span class="pagination-btn disabled">
                                            ›
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
