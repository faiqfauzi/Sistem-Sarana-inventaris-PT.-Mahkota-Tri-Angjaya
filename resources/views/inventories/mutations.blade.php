@extends('layouts.app')

@section('content')

    <div class="container-xl">

        <div class="page-header d-print-none mb-3">

            <div class="row align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Riwayat Pemindahan & Pemusnahan
                    </h2>

                </div>

                <div class="col-auto">

                    @if ($canManageHistory)
                        <a href="{{ route('mutations.create', $inventory) }}" class="btn btn-primary">
                            Tambah Mutasi
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
                    Daftar Riwayat Pemindahan & Pemusnahan
                </h3>

            </div>


            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Alasan</th>
                            <th>Dokumen</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($mutations as $mutation)
                            <tr>

                                <td>
                                    {{ $loop->iteration + ($mutations->firstItem() - 1) }}
                                </td>

                                <td>
                                    {{ $mutation->tanggal?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    {{ $mutation->jenis }}
                                </td>

                                <td>
                                    {{ $mutation->alasan }}
                                </td>

                                <td>

                                    @if ($mutation->dokumen)
                                        <a href="{{ asset('storage/' . $mutation->dokumen) }}" target="_blank"
                                            class="btn btn-primary btn-sm">

                                            Lihat Dokumen

                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>

                                <td>
                                    @if ($canManageHistory)
                                        <a href="{{ route('mutations.edit', $mutation) }}"
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
                                        <form action="{{ route('mutations.destroy', $mutation) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Hapus data mutasi?')"
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

                                    Belum ada riwayat mutasi.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            @if ($mutations->hasPages())
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="pagination-wrapper">

                            <div class="pagination-group">

                                {{-- INFO --}}
                                <div class="pagination-info">
                                    Showing
                                    <strong>{{ $mutations->firstItem() }}</strong>
                                    to
                                    <strong>{{ $mutations->lastItem() }}</strong>
                                    of
                                    <strong>{{ $mutations->total() }}</strong>
                                    results
                                </div>


                                {{-- PAGINATION --}}
                                <div class="pagination-nav">

                                    {{-- PREVIOUS --}}
                                    @if ($mutations->onFirstPage())
                                        <span class="pagination-btn disabled">
                                            ‹
                                        </span>
                                    @else
                                        <a href="{{ $mutations->previousPageUrl() }}" class="pagination-btn">
                                            ‹
                                        </a>
                                    @endif


                                    @php
                                        $currentPage = $mutations->currentPage();
                                        $lastPage = $mutations->lastPage();
                                    @endphp


                                    {{-- JIKA HALAMAN SEDIKIT --}}
                                    @if ($lastPage <= 5)
                                        @for ($page = 1; $page <= $lastPage; $page++)
                                            @if ($page == $currentPage)
                                                <span class="pagination-btn active">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $mutations->url($page) }}" class="pagination-btn">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor


                                        {{-- JIKA HALAMAN BANYAK --}}
                                    @else
                                        {{-- HALAMAN PERTAMA --}}
                                        @if ($currentPage == 1)
                                            <span class="pagination-btn active">
                                                1
                                            </span>
                                        @else
                                            <a href="{{ $mutations->url(1) }}" class="pagination-btn">
                                                1
                                            </a>
                                        @endif


                                        {{-- HALAMAN 2 --}}
                                        @if ($currentPage == 2)
                                            <span class="pagination-btn active">
                                                2
                                            </span>
                                        @else
                                            <a href="{{ $mutations->url(2) }}" class="pagination-btn">
                                                2
                                            </a>
                                        @endif


                                        {{-- HALAMAN 3 --}}
                                        @if ($currentPage == 3)
                                            <span class="pagination-btn active">
                                                3
                                            </span>
                                        @else
                                            <a href="{{ $mutations->url(3) }}" class="pagination-btn">
                                                3
                                            </a>
                                        @endif


                                        {{-- TITIK-TITIK --}}
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
                                                    <a href="{{ $mutations->url($page) }}" class="pagination-btn">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endfor


                                        {{-- TITIK-TITIK SEBELUM HALAMAN TERAKHIR --}}
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
                                            <a href="{{ $mutations->url($lastPage) }}" class="pagination-btn">
                                                {{ $lastPage }}
                                            </a>
                                        @endif
                                    @endif


                                    {{-- NEXT --}}
                                    @if ($mutations->hasMorePages())
                                        <a href="{{ $mutations->nextPageUrl() }}" class="pagination-btn">
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
