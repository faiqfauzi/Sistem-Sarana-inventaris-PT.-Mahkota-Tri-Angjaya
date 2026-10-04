@extends('layouts.app')

@section('title', 'Detail Sarana Inventaris')

@section('content')
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="page-header d-print-none mb-4">
            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Sarana Inventaris
                    </h2>

                    <div class="text-secondary">
                        {{ $masterSaranaInventaris->nama_sarana_inventaris }}
                    </div>

                </div>

                <div class="col-auto">

                    <a href="{{ route('master-sarana-inventaris.index') }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Kembali

                    </a>

                </div>

            </div>
        </div>


        {{-- RINGKASAN --}}
        <div class="card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Ringkasan Penanggung Jawab
                </h3>

            </div>

            <div class="table-responsive">

                <table class="table table-vcenter table-hover mb-0">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Penanggung Jawab</th>
                            <th class="text-center" width="180">
                                Jumlah Sarana
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($summary as $item)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $item['nama'] }}
                                </td>

                                <td class="text-center">
                                    {{ $item['jumlah'] }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center py-4">
                                    Belum ada data penanggung jawab.
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- DAFTAR INVENTARIS --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Daftar Sarana Inventaris
                </h3>

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
                                User
                            </th>

                            <th>
                                Penanggung Jawab
                            </th>

                            <th>
                                Lokasi
                            </th>

                            <th>
                                Spesifikasi
                            </th>

                            <th>
                                Tanggal Perolehan
                            </th>

                            <th width="100">
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

                                    {{ $inventory->user }}

                                </td>

                                <td>

                                    {{ $inventory->responsible?->nama_penanggung_jawab ?? '-' }}

                                </td>

                                <td>

                                    {{ $inventory->location?->nama_lokasi ?? '-' }}

                                </td>

                                <td>

                                    {{ $inventory->spesifikasi ?? '-' }}

                                </td>

                                <td>

                                    {{ $inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}

                                </td>

                                <td>

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

                                <td colspan="8" class="text-center py-4">

                                    Belum ada sarana inventaris.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($inventories->hasPages())

                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="pagination-wrapper">

                            <div class="pagination-group">

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


                                    {{-- PAGE NUMBER --}}
                                    @php
                                        $currentPage = $inventories->currentPage();
                                        $lastPage = $inventories->lastPage();

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

                                        // Hapus duplikat dan urutkan
                                        $pages = array_unique($pages);

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


                                        {{-- HALAMAN --}}
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

                </div>

            @endif

        </div>

    </div>
@endsection
