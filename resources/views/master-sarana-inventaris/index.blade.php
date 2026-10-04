@extends('layouts.app')

@section('title', 'Master Sarana Inventaris')

@section('content')

    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp
    <div class="page-header d-print-none mb-3">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <div class="col">
                    <h2 class="page-title">
                        Master Sarana Inventaris
                    </h2>

                    <div class="text-secondary">
                        Daftar Master Sarana Inventaris
                    </div>
                </div>

                <div class="col-auto ms-auto">

                    @if ($isAdmin)
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editHeaderModal">

                            <i class="ti ti-photo me-1"></i>
                            Edit Gambar

                        </button>
                    @endif

                    {{-- HANYA ADMIN BOLEH TAMBAH --}}
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('master-sarana-inventaris.create') }}" class="btn btn-primary">

                                <i class="ti ti-plus me-1"></i>
                                Tambah

                            </a>
                        @endif
                    @endauth

                </div>

            </div>
        </div>
    </div>

    <div class="container-xl">

        <div class="master-kop-wrapper">

            @php
                $headerImage = null;

                foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                    $path = 'storage/images/daftar-sarana-inventaris.' . $extension;

                    if (file_exists(public_path($path))) {
                        $headerImage = asset($path);
                        break;
                    }
                }
            @endphp

            @if ($headerImage)
                <img src="{{ $headerImage }}" alt="Kop Perusahaan" class="master-kop">
            @else
                <img src="{{ asset('images/daftar-sarana-inventaris.png') }}" alt="Kop Perusahaan" class="master-kop">
            @endif

        </div>

    </div>


    <div class="container-xl master-table-container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="card">

            <div class="card-header">

                <form method="GET" action="{{ route('master-sarana-inventaris.index') }}" class="w-100">

                    <div class="row">

                        <div class="col-md-4">

                            <input type="text" name="search" class="form-control" placeholder="Cari Sarana..."
                                value="{{ request('search') }}">

                        </div>


                        <div class="col-md-auto">

                            <button class="btn btn-primary">
                                Cari
                            </button>

                            <a href="{{ route('master-sarana-inventaris.index') }}" class="btn btn-secondary">
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

                            <th width="70">
                                No
                            </th>

                            <th>
                                Nama Sarana Inventaris
                            </th>

                            <th class="text-center" width="180">
                                Jumlah Sarana
                            </th>

                            <th class="text-center" width="200">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($masterSaranaInventaris as $saranaInventaris)
                            <tr>

                                <td>
                                    {{ $loop->iteration + ($masterSaranaInventaris->firstItem() - 1) }}
                                </td>

                                <td>
                                    {{ $saranaInventaris->nama_sarana_inventaris }}
                                </td>

                                <td class="text-center">
                                    {{ $saranaInventaris->inventories_count }}

                                    {{ $saranaInventaris->inventories_count === 1 ? 'Sarana' : 'Sarana' }}
                                </td>

                                <td class="text-center">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('master-sarana-inventaris.show', $saranaInventaris) }}"
                                        class="btn btn-info btn-sm btn-icon" title="Detail" aria-label="Detail">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">

                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />

                                            <circle cx="12" cy="12" r="3" />

                                        </svg>

                                    </a>


                                    {{-- EDIT & HAPUS ADMIN SAJA --}}
                                    @if ($isAdmin)
                                        <a href="{{ route('master-sarana-inventaris.edit', $saranaInventaris) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 20h9" />

                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                            </svg>

                                        </a>


                                        <form action="{{ route('master-sarana-inventaris.destroy', $saranaInventaris) }}"
                                            method="POST" class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus sarana ini?')"
                                                class="btn btn-danger btn-sm btn-icon" title="Hapus" aria-label="Hapus">

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

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-5">

                                    Tidak ada data sarana inventaris.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($masterSaranaInventaris->hasPages())

                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="pagination-wrapper">

                            <div class="pagination-group">

                                <div class="pagination-info">

                                    Showing

                                    <strong>
                                        {{ $masterSaranaInventaris->firstItem() }}
                                    </strong>

                                    to

                                    <strong>
                                        {{ $masterSaranaInventaris->lastItem() }}
                                    </strong>

                                    of

                                    <strong>
                                        {{ $masterSaranaInventaris->total() }}
                                    </strong>

                                    results

                                </div>


                                <div class="pagination-nav">

                                    {{-- SEBELUMNYA --}}
                                    @if ($masterSaranaInventaris->onFirstPage())
                                        <span class="pagination-btn disabled">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $masterSaranaInventaris->previousPageUrl() }}"
                                            class="pagination-btn">
                                            ‹
                                        </a>
                                    @endif


                                    {{-- NOMOR HALAMAN --}}
                                    @php
                                        $currentPage = $masterSaranaInventaris->currentPage();
                                        $lastPage = $masterSaranaInventaris->lastPage();

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

                                        // Hilangkan halaman duplikat
                                        $pages = array_unique($pages);

                                        // Urutkan halaman
                                        sort($pages);

                                        $previousPage = null;
                                    @endphp


                                    @foreach ($pages as $page)
                                        {{-- Tampilkan ... jika ada halaman yang dilewati --}}
                                        @if ($previousPage !== null && $page > $previousPage + 1)
                                            <span class="pagination-dots">
                                                ...
                                            </span>
                                        @endif


                                        {{-- HALAMAN AKTIF --}}
                                        @if ($page == $currentPage)
                                            <span class="pagination-btn active">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $masterSaranaInventaris->url($page) }}" class="pagination-btn">

                                                {{ $page }}

                                            </a>
                                        @endif


                                        @php
                                            $previousPage = $page;
                                        @endphp
                                    @endforeach


                                    {{-- SELANJUTNYA --}}
                                    @if ($masterSaranaInventaris->hasMorePages())
                                        <a href="{{ $masterSaranaInventaris->nextPageUrl() }}" class="pagination-btn">
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

    @if ($isAdmin)
        <div class="modal fade" id="editHeaderModal" tabindex="-1" aria-labelledby="editHeaderModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <form method="POST" action="{{ route('master-sarana-inventaris.update-header') }}"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title" id="editHeaderModalLabel">
                                Edit Gambar Header
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    Pilih Gambar Baru
                                </label>

                                <input type="file" name="header" class="form-control" accept=".jpg,.jpeg,.png,.webp"
                                    required>

                                <div class="form-text">
                                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>
                                Simpan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    @endif

@endsection
