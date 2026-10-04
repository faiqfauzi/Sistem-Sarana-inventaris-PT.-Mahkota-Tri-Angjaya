@extends('layouts.app')

@section('title', 'Detail Penanggung Jawab')

@section('content')

    <div class="page-header d-print-none mb-3">
        <div class="container-xl">

            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Penanggung Jawab
                    </h2>

                    <div class="text-secondary">
                        {{ $responsible->nama_penanggung_jawab }}
                    </div>

                </div>

                <div class="col-auto ms-auto">

                    <a href="{{ route('responsibles.index') }}" class="btn btn-secondary">

                        Kembali

                    </a>

                </div>

            </div>

        </div>
    </div>


    <div class="container-xl">

        {{-- =========================================
            RINGKASAN SARANA INVENTARIS
        ========================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Ringkasan Sarana Inventaris
                </h3>

            </div>


            <div class="table-responsive">

                <table class="table table-vcenter table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Sarana Inventaris
                            </th>

                            <th class="text-center" width="180">
                                Jumlah
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($summary as $item)
                            <tr>

                                <td>
                                    {{ $item['nama'] }}
                                </td>

                                <td class="text-center">
                                    {{ $item['jumlah'] }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2" class="text-center py-4">

                                    Belum ada sarana inventaris.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- =========================================
    DOKUMEN STOCK OPNAME
========================================== --}}
        @php
            $user = auth()->user();

            $canManageStockOpnameDocument =
                $user &&
                ($user->role === 'admin' ||
                    ($user->role === 'responsible' && (int) $user->responsible_id === (int) $responsible->id));
        @endphp

        @if ($canManageStockOpnameDocument)
            <div class="card mb-4">

                <div class="card-header">

                    <h3 class="card-title">
                        Dokumen Stock Opname
                    </h3>

                </div>

                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            @if ($responsible->stock_opname_document)
                                <div class="fw-bold mb-1">
                                    Dokumen Stock Opname tersedia
                                </div>

                                <div class="text-secondary">
                                    Upload terakhir:
                                    {{ $responsible->stock_opname_uploaded_at?->format('d-m-Y') ?? '-' }}
                                </div>
                            @else
                                <div class="fw-bold mb-1">
                                    Belum ada dokumen Stock Opname
                                </div>

                                <div class="text-secondary">
                                    Silakan upload dokumen Stock Opname untuk penanggung jawab ini.
                                </div>
                            @endif

                        </div>


                        <div class="col-auto">

                            <div class="d-flex gap-2">

                                @if ($responsible->stock_opname_document)
                                    <a href="{{ route('responsibles.view-stock-opname', $responsible) }}"
                                        class="btn btn-info" target="_blank">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>

                                        Lihat Dokumen

                                    </a>
                                @endif


                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#uploadStockOpnameModal">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                        <path d="M12 3v12" />
                                        <path d="m7 8 5-5 5 5" />
                                        <path d="M5 15v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4" />
                                    </svg>

                                    {{ $responsible->stock_opname_document ? 'Ganti Dokumen' : 'Upload Dokumen' }}

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================
        MODAL UPLOAD STOCK OPNAME
    ========================================== --}}
            <div class="modal modal-blur fade" id="uploadStockOpnameModal" tabindex="-1" aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <form action="{{ route('responsibles.upload-stock-opname', $responsible) }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    {{ $responsible->stock_opname_document ? 'Ganti Dokumen Stock Opname' : 'Upload Dokumen Stock Opname' }}
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                            </div>


                            <div class="modal-body">

                                @if ($responsible->stock_opname_document)
                                    <div class="alert alert-warning">

                                        <div class="fw-bold mb-1">
                                            Perhatian
                                        </div>

                                        <div>
                                            Dokumen yang diupload akan menggantikan dokumen Stock Opname sebelumnya.
                                        </div>

                                    </div>
                                @endif


                                <div class="mb-3">

                                    <label class="form-label">
                                        Dokumen Stock Opname
                                    </label>

                                    <input type="file" name="document" class="form-control"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx" required>

                                    <div class="form-hint">
                                        Format yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX.
                                        Maksimal 10 MB.
                                    </div>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    Upload Dokumen
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        @endif



        {{-- =========================================
            DAFTAR SARANA INVENTARIS
        ========================================== --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Daftar Sarana Inventaris
                </h3>

                <div class="card-actions">

                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('responsibles.document-stock-opname', $responsible) }}"
                            class="btn btn-primary btn-sm" target="_blank">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-1">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <path d="M8 13h8" />
                                <path d="M8 17h5" />
                            </svg>

                            Document

                        </a>
                    @endif

                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-vcenter table-hover">

                    <thead>

                        <tr>

                            <th width="70">
                                No
                            </th>

                            <th>
                                Nomor Inventaris
                            </th>

                            <th>
                                Sarana Inventaris
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Lokasi
                            </th>

                            <th>
                                Spesifikasi
                            </th>

                            <th width="150">
                                Tanggal Perolehan
                            </th>

                            <th width="120">
                                Status
                            </th>

                            <th class="text-center" width="100">

                                Aksi

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($inventories as $inventory)
                            <tr>

                                <td>
                                    {{ $loop->iteration + ($inventories->firstItem() - 1) }}
                                </td>


                                <td>
                                    {{ $inventory->nomor_inventaris }}
                                </td>


                                <td>
                                    {{ $inventory->masterSaranaInventaris->nama_sarana_inventaris }}
                                </td>


                                <td>
                                    {{ $inventory->user }}
                                </td>


                                <td>
                                    {{ $inventory->location->nama_lokasi }}
                                </td>


                                <td>
                                    {{ $inventory->spesifikasi }}
                                </td>


                                <td>
                                    {{ $inventory->tanggal_perolehan?->format('d-m-Y') }}
                                </td>


                                <td>
                                    {{ $inventory->status }}
                                </td>


                                <td class="text-center">

                                    <a href="{{ route('inventories.show', $inventory) }}"
                                        class="btn btn-info btn-sm btn-icon" title="Detail" aria-label="Detail">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">

                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />

                                            <circle cx="12" cy="12" r="3" />

                                        </svg>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-5">

                                    Belum ada inventaris.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- =========================================
                PAGINATION
            ========================================== --}}
            @if ($inventories->hasPages())

                <div class="card-footer">

                    <div class="pagination-wrapper">

                        <div class="pagination-group">


                            {{-- INFO --}}
                            <div class="pagination-info">

                                Showing

                                <strong>
                                    {{ $inventories->firstItem() }}
                                </strong>

                                to

                                <strong>
                                    {{ $inventories->lastItem() }}
                                </strong>

                                of

                                <strong>
                                    {{ $inventories->total() }}
                                </strong>

                                results

                            </div>


                            {{-- NAVIGATION --}}
                            <div class="pagination-nav">


                                {{-- PREVIOUS --}}
                                @if ($inventories->onFirstPage())
                                    <span class="pagination-btn disabled">
                                        ‹
                                    </span>
                                @else
                                    <a href="{{ $inventories->previousPageUrl() }}" class="pagination-btn">
                                        ‹
                                    </a>
                                @endif


                                {{-- PAGE NUMBERS --}}
                                @php
                                    $currentPage = $inventories->currentPage();
                                    $lastPage = $inventories->lastPage();

                                    $pages = [];

                                    // Selalu tampilkan halaman pertama
                                    $pages[] = 1;

                                    // Tampilkan halaman sebelum, aktif, dan sesudah
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

                                    // Urutkan halaman
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
                                        <a href="{{ $inventories->url($page) }}" class="pagination-btn">
                                            {{ $page }}
                                        </a>
                                    @endif


                                    @php
                                        $previousPage = $page;
                                    @endphp
                                @endforeach


                                {{-- NEXT --}}
                                @if ($inventories->hasMorePages())
                                    <a href="{{ $inventories->nextPageUrl() }}" class="pagination-btn">
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

            @endif

        </div>

    </div>

@endsection
