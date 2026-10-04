@extends('layouts.app')

@section('title', 'Penanggung Jawab')

@section('content')

    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp


    {{-- ============================================================
        HEADER HALAMAN
    ============================================================ --}}

    <div class="page-header d-print-none mb-3">

        <div class="container-xl">

            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Penanggung Jawab
                    </h2>

                    <div class="text-secondary">
                        Master Data Penanggung Jawab
                    </div>

                </div>


                {{-- TOMBOL TAMBAH HANYA ADMIN --}}
                <div class="col-auto ms-auto">

                    @if ($isAdmin)
                        <a href="{{ route('responsibles.create') }}" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            Tambah

                        </a>
                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
        CONTENT
    ============================================================ --}}

    <div class="container-xl">


        {{-- NOTIFIKASI SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif



        <div class="card">


            {{-- ====================================================
                SEARCH
            ==================================================== --}}

            <div class="card-header">

                <form method="GET" action="{{ route('responsibles.index') }}" class="w-100">

                    <div class="row g-2 align-items-center">


                        {{-- INPUT SEARCH --}}
                        <div class="col-md-4">

                            <input type="text" name="search" class="form-control" placeholder="Cari penanggung jawab..."
                                value="{{ request('search') }}">

                        </div>


                        {{-- BUTTON --}}
                        <div class="col-md-auto">

                            <button type="submit" class="btn btn-primary">

                                Cari

                            </button>


                            <a href="{{ route('responsibles.index') }}" class="btn btn-secondary">

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>



            {{-- ====================================================
                TABLE
            ==================================================== --}}

            <div class="table-responsive">

                <table class="table table-vcenter table-hover">


                    {{-- HEADER --}}
                    <thead>

                        <tr>

                            <th width="70" class="text-center">

                                No

                            </th>


                            <th>

                                Nama Penanggung Jawab

                            </th>


                            <th width="160" class="text-center">

                                Jumlah Sarana

                            </th>


                            <th width="180" class="text-center text-nowrap">

                                Aksi

                            </th>

                        </tr>

                    </thead>



                    {{-- BODY --}}
                    <tbody>

                        @forelse($responsibles as $responsible)
                            <tr>


                                {{-- NOMOR --}}
                                <td class="text-center">

                                    {{ $loop->iteration + ($responsibles->firstItem() - 1) }}

                                </td>


                                {{-- NAMA --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $responsible->nama_penanggung_jawab }}

                                    </div>

                                </td>


                                {{-- JUMLAH SARANA --}}
                                <td class="text-center">

                                    <span>

                                        {{ $responsible->inventories_count }}

                                        Sarana

                                    </span>

                                </td>


                                {{-- AKSI --}}
                                <td class="text-center text-nowrap">


                                    {{-- DETAIL: SEMUA ORANG --}}
                                    <a href="{{ route('responsibles.show', $responsible) }}"
                                        class="btn btn-info btn-sm btn-icon" title="Detail" aria-label="Detail">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">

                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />

                                            <circle cx="12" cy="12" r="3" />

                                        </svg>

                                    </a>



                                    {{-- EDIT DAN HAPUS: ADMIN SAJA --}}
                                    @if ($isAdmin)
                                        {{-- EDIT --}}
                                        <a href="{{ route('responsibles.edit', $responsible) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 20h9" />

                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                            </svg>

                                        </a>



                                        {{-- HAPUS --}}
                                        <form action="{{ route('responsibles.destroy', $responsible) }}" method="POST"
                                            class="d-inline">

                                            @csrf

                                            @method('DELETE')


                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus penanggung jawab ini?')"
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


                            {{-- ====================================================
                            DATA KOSONG
                        ==================================================== --}}

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-5 text-secondary">

                                    Tidak ada data penanggung jawab.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- ====================================================
                PAGINATION
            ==================================================== --}}

            @if ($responsibles->hasPages())

                <div class="card-footer">

                    <div class="pagination-wrapper">

                        <div class="pagination-group">


                            {{-- INFO --}}
                            <div class="pagination-info">

                                Showing

                                <strong>
                                    {{ $responsibles->firstItem() }}
                                </strong>

                                to

                                <strong>
                                    {{ $responsibles->lastItem() }}
                                </strong>

                                of

                                <strong>
                                    {{ $responsibles->total() }}
                                </strong>

                                results

                            </div>


                            {{-- NAVIGATION --}}
                            <div class="pagination-nav">


                                {{-- PREVIOUS --}}
                                @if ($responsibles->onFirstPage())
                                    <span class="pagination-btn disabled">
                                        ‹
                                    </span>
                                @else
                                    <a href="{{ $responsibles->previousPageUrl() }}" class="pagination-btn">
                                        ‹
                                    </a>
                                @endif


                                {{-- PAGE NUMBER --}}
                                @php
                                    $currentPage = $responsibles->currentPage();
                                    $lastPage = $responsibles->lastPage();

                                    $pages = [];

                                    // Selalu tampilkan halaman pertama
                                    $pages[] = 1;

                                    // Halaman sebelum, aktif, dan sesudah
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
                                        <a href="{{ $responsibles->url($page) }}" class="pagination-btn">

                                            {{ $page }}

                                        </a>
                                    @endif


                                    @php
                                        $previousPage = $page;
                                    @endphp
                                @endforeach


                                {{-- NEXT --}}
                                @if ($responsibles->hasMorePages())
                                    <a href="{{ $responsibles->nextPageUrl() }}" class="pagination-btn">

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
