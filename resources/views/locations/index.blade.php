@extends('layouts.app')

@section('title', 'Lokasi')

@section('content')

    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp
    <div class="page-header d-print-none mb-3">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Lokasi
                    </h2>
                    <div class="text-secondary">
                        Lokasi Sarana Inventaris
                    </div>
                </div>

                <div class="col-auto ms-auto">
                    <a href="{{ route('locations.print.qr') }}" class="btn btn-success">

                        Print Semua QR

                    </a>
                    @if ($isAdmin)
                        <a href="{{ route('locations.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            Tambah
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">

            <div class="card-header">

                <form method="GET" action="{{ route('locations.index') }}" class="w-100">

                    <div class="row">

                        <div class="col-md-4">

                            <input type="text" name="search" class="form-control" placeholder="Cari lokasi..."
                                value="{{ request('search') }}">

                        </div>

                        <div class="col-md-auto">

                            <button class="btn btn-primary">
                                Cari
                            </button>

                            <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

            <div class="table-responsive">

                <table class="table table-vcenter table-hover">

                    <thead>

                        <tr>
                            <th width="70" class="text-center">
                                No
                            </th>

                            <th>
                                Nama Lokasi
                            </th>

                            <th>
                                Keterangan
                            </th>

                            <th width="150" class="text-center">
                                Jumlah Sarana
                            </th>

                            <th width="180" class="text-center">
                                Aksi
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($locations as $location)
                            <tr>

                                <td class="text-center">

                                    {{ $loop->iteration + ($locations->firstItem() - 1) }}

                                </td>


                                <td>

                                    <strong>
                                        {{ $location->nama_lokasi }}
                                    </strong>

                                </td>


                                <td class="text-secondary">

                                    {{ $location->keterangan ?: '-' }}

                                </td>


                                <td class="text-center">

                                    <span>

                                        {{ $location->inventories_count }}

                                        {{ $location->inventories_count === 1 ? 'Sarana' : 'Sarana' }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <div class="btn-list justify-content-center flex-nowrap">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('locations.show', $location) }}"
                                            class="btn btn-info btn-sm btn-icon" title="Detail" aria-label="Detail">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />

                                                <circle cx="12" cy="12" r="3" />

                                            </svg>

                                        </a>


                                        {{-- ADMIN ONLY --}}
                                        @if ($isAdmin)
                                            {{-- EDIT --}}
                                            <a href="{{ route('locations.edit', $location) }}"
                                                class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                    <path d="M12 20h9" />

                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                                </svg>

                                            </a>


                                            {{-- HAPUS --}}
                                            <form action="{{ route('locations.destroy', $location) }}" method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus lokasi ini?')"
                                                    class="btn btn-danger btn-sm btn-icon" title="Hapus"
                                                    aria-label="Hapus">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                        <path d="M3 6h18" />

                                                        <path d="M8 6V4h8v2" />

                                                        <path d="M19 6v14H5V6" />

                                                        <path d="M10 11v6" />

                                                        <path d="M14 11v6" />

                                                    </svg>

                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-5 text-secondary">

                                    Tidak ada data lokasi.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($locations->hasPages())
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="pagination-wrapper">

                            <div class="pagination-group">

                                {{-- INFO --}}
                                <div class="pagination-info">
                                    Showing
                                    <strong>{{ $locations->firstItem() }}</strong>
                                    to
                                    <strong>{{ $locations->lastItem() }}</strong>
                                    of
                                    <strong>{{ $locations->total() }}</strong>
                                    results
                                </div>


                                {{-- PAGINATION --}}
                                <div class="pagination-nav">

                                    {{-- PREVIOUS --}}
                                    @if ($locations->onFirstPage())
                                        <span class="pagination-btn disabled">‹</span>
                                    @else
                                        <a href="{{ $locations->previousPageUrl() }}" class="pagination-btn">
                                            ‹
                                        </a>
                                    @endif


                                    @php
                                        $currentPage = $locations->currentPage();
                                        $lastPage = $locations->lastPage();
                                    @endphp


                                    {{-- JIKA HALAMAN SEDIKIT --}}
                                    @if ($lastPage <= 5)

                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            @if ($page == $currentPage)
                                                <span class="pagination-btn active">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $locations->url($page) }}" class="pagination-btn">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor


                                        {{-- JIKA HALAMAN BANYAK --}}
                                    @else
                                        {{-- HALAMAN 1 --}}
                                        @if ($currentPage == 1)
                                            <span class="pagination-btn active">1</span>
                                        @else
                                            <a href="{{ $locations->url(1) }}" class="pagination-btn">
                                                1
                                            </a>
                                        @endif


                                        {{-- HALAMAN 2 --}}
                                        @if ($currentPage == 2)
                                            <span class="pagination-btn active">2</span>
                                        @else
                                            <a href="{{ $locations->url(2) }}" class="pagination-btn">
                                                2
                                            </a>
                                        @endif


                                        {{-- HALAMAN 3 --}}
                                        @if ($currentPage == 3)
                                            <span class="pagination-btn active">3</span>
                                        @else
                                            <a href="{{ $locations->url(3) }}" class="pagination-btn">
                                                3
                                            </a>
                                        @endif


                                        {{-- TITIK-TITIK AWAL --}}
                                        @if ($currentPage > 4)
                                            <span class="pagination-ellipsis">
                                                ...
                                            </span>
                                        @endif


                                        {{-- HALAMAN SEKITAR HALAMAN AKTIF --}}
                                        @for ($page = max(4, $currentPage - 1); $page <= min($lastPage - 1, $currentPage + 1); $page++)
                                            @if ($page > 3 && $page < $lastPage)
                                                @if ($page == $currentPage)
                                                    <span class="pagination-btn active">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $locations->url($page) }}" class="pagination-btn">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endfor


                                        {{-- TITIK-TITIK AKHIR --}}
                                        @if ($currentPage < $lastPage - 3)
                                            <span class="pagination-ellipsis">
                                                ...
                                            </span>
                                        @endif


                                        {{-- HALAMAN TERAKHIR --}}
                                        @if ($currentPage == $lastPage)
                                            <span class="pagination-btn active">
                                                {{ $lastPage }}
                                            </span>
                                        @else
                                            <a href="{{ $locations->url($lastPage) }}" class="pagination-btn">
                                                {{ $lastPage }}
                                            </a>
                                        @endif

                                    @endif


                                    {{-- NEXT --}}
                                    @if ($locations->hasMorePages())
                                        <a href="{{ $locations->nextPageUrl() }}" class="pagination-btn">
                                            ›
                                        </a>
                                    @else
                                        <span class="pagination-btn disabled">›</span>
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
