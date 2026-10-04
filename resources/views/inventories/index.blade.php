@extends('layouts.app')

@section('title', 'Sarana Inventaris')

@section('content')
    <div class="page-header d-print-none mb-3">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <div class="col">
                    <h2 class="page-title">
                        Sarana Inventaris
                    </h2>

                    <div class="text-secondary">
                        Daftar Sarana Inventaris
                    </div>
                </div>

                <div class="col-auto ms-auto">

                    {{-- Semua role boleh Print QR --}}
                    <a href="{{ route('inventories.print.qr') }}" class="btn btn-success normal-mode-button">

                        Print Semua QR

                    </a>


                    {{-- Hanya user login --}}
                    @auth

                        {{-- ADMIN DAN RESPONSIBLE BOLEH TAMBAH --}}
                        <a href="{{ route('inventories.create') }}" class="btn btn-primary normal-mode-button">

                            <i class="ti ti-plus me-1"></i>

                            Tambah

                        </a>

                    @endauth


                    {{-- HANYA ADMIN BOLEH SELECT DAN BULK DELETE --}}
                    @auth

                        @if (auth()->user()->role === 'admin')
                            <button type="button" id="select-mode-btn" class="btn btn-secondary">

                                Select

                            </button>


                            <button type="button" id="finish-select-btn" class="btn btn-secondary" style="display: none;">

                                Selesai

                            </button>


                            <button type="submit" form="bulk-delete-form" id="bulk-delete-button" class="btn btn-danger"
                                disabled style="display: none;">

                                Hapus Terpilih

                            </button>
                        @endif

                    @endauth

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
            <form action="{{ route('inventories.bulk-destroy') }}" method="POST" id="bulk-delete-form">

                @csrf
                @method('DELETE')

            </form>

            <div class="card-header">

                <form action="{{ route('inventories.index') }}" method="GET" class="w-100">

                    <div class="row">

                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-auto">
                            <button class="btn btn-primary">
                                Cari
                            </button>

                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>

                    </div>

                </form>

            </div>

            <div class="table-responsive">

                <table class="table table-vcenter table-hover inventory-table">

                    <thead>

                        <tr>
                            @auth

                                @if (auth()->user()->role === 'admin')
                                    <th class="select-column text-center" style="display: none; width: 45px;">

                                        <input type="checkbox" id="select-all">

                                    </th>
                                @endif

                            @endauth

                            <th>No</th>
                            <th>No. Inventaris</th>
                            <th>Sarana Inventaris</th>
                            <th>User</th>
                            <th>Penanggung Jawab</th>
                            <th>Lokasi</th>
                            <th>Spesifikasi</th>
                            <th>Tanggal Perolehan</th>
                            @php
                                $sort = request('sort');
                                $direction = request('direction');
                            @endphp

                            <th style="width: 210px; white-space: nowrap;">

                                <a href="{{ route('inventories.index', [
                                    'search' => request('search'),
                                    'sort' => 'stock_opname',
                                    'direction' => $sort == 'stock_opname' && $direction == 'asc' ? 'desc' : 'asc',
                                ]) }}"
                                    class="text-dark text-decoration-none d-inline-flex align-items-center gap-1">

                                    Update Stok Opname

                                    @if ($sort == 'stock_opname')

                                        @if ($direction == 'asc')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 5v14" />
                                                <path d="m18 11-6-6-6 6" />

                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 19V5" />
                                                <path d="m6 13 6 6 6-6" />

                                            </svg>
                                        @endif
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">

                                            <path d="M7 15V3" />
                                            <path d="m3 7 4-4 4 4" />

                                            <path d="M17 9v12" />
                                            <path d="m21 17-4 4-4-4" />

                                        </svg>

                                    @endif

                                </a>

                            </th>
                            <th class="action-column" width="220">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($inventories as $inventory)
                            <tr>

                                @auth

                                    @if (auth()->user()->role === 'admin')
                                        <td class="select-column text-center" style="display: none;">

                                            <input type="checkbox" name="ids[]" value="{{ $inventory->id }}"
                                                class="inventory-checkbox" form="bulk-delete-form">

                                        </td>
                                    @endif

                                @endauth

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
                                    {{ $inventory->responsible->nama_penanggung_jawab }}
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
                                    @if ($inventory->latestStockOpname)
                                        {{ $inventory->latestStockOpname->tanggal->format('d-m-Y') }}
                                    @else
                                        <span class="badge bg-danger text-white">
                                            Belum Pernah
                                        </span>
                                    @endif

                                </td>

                                <td class="action-column">

                                    {{-- DETAIL: SEMUA ORANG BOLEH --}}
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


                                    {{-- ============================================
        EDIT
    ============================================= --}}

                                    @auth

                                        {{-- ADMIN BOLEH EDIT SEMUA --}}
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('inventories.edit', $inventory) }}"
                                                class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                    <path d="M12 20h9" />

                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                                </svg>

                                            </a>


                                            {{-- RESPONSIBLE HANYA EDIT INVENTARIS MILIKNYA --}}
                                        @elseif (auth()->user()->responsible_id && auth()->user()->responsible_id === $inventory->responsible_id)
                                            <a href="{{ route('inventories.edit', $inventory) }}"
                                                class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                    <path d="M12 20h9" />

                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                                </svg>

                                            </a>
                                        @endif


                                        {{-- ============================================
            HAPUS: HANYA ADMIN
        ============================================= --}}

                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('inventories.destroy', $inventory) }}" method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus sarana inventaris ini?')"
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

                                    @endauth

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-5">

                                    Belum ada data inventaris.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($inventories->hasPages())
                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-wrapper">

                            <div class="pagination-group">

                                <div class="pagination-info">
                                    Showing
                                    <strong>{{ $inventories->firstItem() }}</strong>
                                    to
                                    <strong>{{ $inventories->lastItem() }}</strong>
                                    of
                                    <strong>{{ $inventories->total() }}</strong>
                                    results
                                </div>

                                <div class="pagination-nav">

                                    @if ($inventories->onFirstPage())
                                        <span class="pagination-btn disabled">‹</span>
                                    @else
                                        <a href="{{ $inventories->previousPageUrl() }}" class="pagination-btn">‹</a>
                                    @endif

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

                                        // Hapus halaman yang duplikat
                                        $pages = array_unique($pages);

                                        // Urutkan nomor halaman
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


                                        {{-- Nomor halaman --}}
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

                                    @if ($inventories->hasMorePages())
                                        <a href="{{ $inventories->nextPageUrl() }}" class="pagination-btn">›</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectModeBtn = document.getElementById('select-mode-btn');
        const finishSelectBtn = document.getElementById('finish-select-btn');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        const selectAll = document.getElementById('select-all');
        const selectColumns = document.querySelectorAll('.select-column');
        const actionColumns = document.querySelectorAll('.action-column');

        const inventoryCheckboxes = document.querySelectorAll('.inventory-checkbox');

        // Tombol yang disembunyikan saat mode Select aktif
        const normalButtons = document.querySelectorAll(
            '.normal-mode-button'
        );

        let selectMode = false;


        // ================================
        // AKTIF / NONAKTIF MODE SELECT
        // ================================

        function updateSelectMode() {

            selectColumns.forEach(function(column) {

                column.style.display = selectMode ? '' : 'none';

            });


            actionColumns.forEach(function(column) {

                column.style.display = selectMode ? 'none' : '';

            });


            selectModeBtn.style.display =
                selectMode ? 'none' : '';

            finishSelectBtn.style.display =
                selectMode ? '' : 'none';

            bulkDeleteButton.style.display =
                selectMode ? '' : 'none';


            normalButtons.forEach(function(button) {

                button.style.display =
                    selectMode ? 'none' : '';

            });


            if (!selectMode) {

                inventoryCheckboxes.forEach(function(checkbox) {

                    checkbox.checked = false;

                });

                selectAll.checked = false;

                updateDeleteButton();

            }

        }


        // ================================
        // UPDATE TOMBOL HAPUS
        // ================================

        function updateDeleteButton() {

            const selected =
                document.querySelectorAll(
                    '.inventory-checkbox:checked'
                ).length;


            if (selected > 0) {

                bulkDeleteButton.disabled = false;

                bulkDeleteButton.textContent =
                    'Hapus ' + selected + ' Terpilih';

            } else {

                bulkDeleteButton.disabled = true;

                bulkDeleteButton.textContent =
                    'Hapus Terpilih';

            }

        }


        // ================================
        // BUTTON SELECT
        // ================================

        selectModeBtn.addEventListener('click', function() {

            selectMode = true;

            updateSelectMode();

        });


        // ================================
        // BUTTON SELESAI
        // ================================

        finishSelectBtn.addEventListener('click', function() {

            selectMode = false;

            updateSelectMode();

        });


        // ================================
        // SELECT ALL
        // ================================

        selectAll.addEventListener('change', function() {

            inventoryCheckboxes.forEach(function(checkbox) {

                checkbox.checked = selectAll.checked;

            });

            updateDeleteButton();

        });


        // ================================
        // CHECKBOX PER DATA
        // ================================

        inventoryCheckboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                const selected =
                    document.querySelectorAll(
                        '.inventory-checkbox:checked'
                    ).length;


                selectAll.checked =
                    selected === inventoryCheckboxes.length;


                updateDeleteButton();

            });

        });


        // ================================
        // KONFIRMASI HAPUS
        // ================================

        bulkDeleteForm.addEventListener('submit', function(event) {

            const selected =
                document.querySelectorAll(
                    '.inventory-checkbox:checked'
                ).length;


            if (selected === 0) {

                event.preventDefault();

                return;

            }


            const confirmed = confirm(
                'Yakin ingin menghapus ' +
                selected +
                ' data inventaris terpilih?'
            );


            if (!confirmed) {

                event.preventDefault();

            }

        });


        // ================================
        // KONDISI AWAL
        // ================================

        updateSelectMode();

    });
</script>
