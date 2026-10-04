@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

    <div class="page-header">
        <div class="container-xl">

            <h2 class="page-title">
                Laporan
            </h2>

        </div>
    </div>


    <div class="page-body">

        <div class="container-xl">


            {{-- ============================================================
            | FORM EXPORT
            ============================================================ --}}

            <form id="report-export-form" action="{{ route('reports.export') }}" method="POST">
                @csrf
            </form>


            {{-- ============================================================
            | FILTER LAPORAN + PILIH KOLOM
            ============================================================ --}}

            <div class="card mb-3">

                <div class="card-header">

                    <h3 class="card-title">
                        Filter Laporan
                    </h3>

                </div>


                <div class="card-body">


                    {{-- ====================================================
                    | FILTER
                    ==================================================== --}}

                    <form method="GET" action="{{ route('reports.index') }}">

                        <div class="row">


                            {{-- DARI TANGGAL --}}

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Dari Tanggal
                                </label>

                                <input type="date" name="tanggal_awal" class="form-control"
                                    value="{{ request('tanggal_awal') }}">

                            </div>


                            {{-- SAMPAI TANGGAL --}}

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Sampai Tanggal
                                </label>

                                <input type="date" name="tanggal_akhir" class="form-control"
                                    value="{{ request('tanggal_akhir') }}">

                            </div>


                            {{-- SARANA INVENTARIS --}}

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Sarana Inventaris
                                </label>

                                <select name="master_sarana_inventaris_id" class="form-select">

                                    <option value="">
                                        Semua
                                    </option>


                                    @foreach ($masterSaranaInventaris as $item)
                                        <option value="{{ $item->id }}" @selected(request('master_sarana_inventaris_id') == $item->id)>

                                            {{ $item->nama_sarana_inventaris }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            {{-- PENANGGUNG JAWAB --}}

                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Penanggung Jawab
                                </label>

                                <select name="responsible_id" class="form-select">

                                    <option value="">
                                        Semua
                                    </option>


                                    @foreach ($responsibles as $responsible)
                                        <option value="{{ $responsible->id }}" @selected(request('responsible_id') == $responsible->id)>

                                            {{ $responsible->nama_penanggung_jawab }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                        </div>


                        {{-- ====================================================
                        | BUTTON FILTER
                        ==================================================== --}}

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">

                                Tampilkan

                            </button>


                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">

                                Reset

                            </a>

                        </div>

                    </form>


                    <hr class="my-4">


                    {{-- ====================================================
                    | PILIH KOLOM LAPORAN
                    ==================================================== --}}

                    <div class="row align-items-end">


                        <div class="col-md-5 mb-3">

                            <label class="form-label">

                                Pilih Kolom Laporan

                            </label>


                            <div class="dropdown">


                                <button class="btn btn-outline-primary dropdown-toggle w-100 text-start" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">

                                    Pilih Kolom Laporan

                                </button>


                                <div class="dropdown-menu p-3 w-100" style="max-height: 400px; overflow-y: auto;">


                                    {{-- PILIH SEMUA KOLOM --}}

                                    <div class="form-check mb-3">

                                        <input class="form-check-input" type="checkbox" id="select-all-columns">

                                        <label class="form-check-label fw-bold" for="select-all-columns">

                                            Pilih Semua Kolom

                                        </label>

                                    </div>


                                    <div class="dropdown-divider"></div>


                                    {{-- KOLOM LAPORAN --}}

                                    @foreach ($availableColumns as $key => $label)
                                        <div class="form-check mb-2">

                                            <input class="form-check-input report-column-checkbox" type="checkbox"
                                                name="columns[]" value="{{ $key }}"
                                                id="column-{{ $key }}" form="report-export-form">

                                            <label class="form-check-label" for="column-{{ $key }}">

                                                {{ $label }}

                                            </label>

                                        </div>
                                    @endforeach


                                </div>

                            </div>

                        </div>


                        {{-- ====================================================
                        | EXPORT BUTTON
                        ==================================================== --}}

                        <div class="col-md-7 mb-3 d-flex gap-2" id="export-buttons" style="display: none;">


                            <button type="button" id="select-mode-btn" class="btn btn-outline-primary">

                                Pilih Inventaris

                            </button>


                            <button type="button" id="finish-select-btn" class="btn btn-outline-secondary"
                                style="display: none;">

                                Selesai Memilih

                            </button>


                            <button type="submit" name="format" value="excel" form="report-export-form"
                                id="export-excel-btn" class="btn btn-success" disabled>

                                Export Excel

                            </button>


                            <button type="submit" name="format" value="pdf" form="report-export-form"
                                id="export-pdf-btn" class="btn btn-danger" disabled>

                                Export PDF

                            </button>


                        </div>


                    </div>


                </div>

            </div>


            {{-- ============================================================
            | HASIL LAPORAN
            ============================================================ --}}

            <div class="card">


                <div class="card-header">

                    <h3 class="card-title">
                        Hasil Laporan
                    </h3>

                </div>


                <div class="card-body">


                    <div class="table-responsive">


                        <table class="table table-hover table-vcenter">


                            <thead>

                                <tr>


                                    {{-- ====================================================
                                    | CHECKBOX PILIH SEMUA INVENTARIS
                                    ==================================================== --}}

                                    <th class="select-column text-center" style="display: none; width: 50px;">

                                        <input type="checkbox" class="form-check-input" id="select-all">

                                    </th>


                                    <th>
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
                                        Status
                                    </th>


                                </tr>

                            </thead>


                            <tbody>


                                @forelse($inventories as $inventory)
                                    <tr>


                                        {{-- CHECKBOX INVENTARIS --}}

                                        <td class="select-column text-center" style="display: none;">

                                            <input type="checkbox" name="inventory_ids[]" value="{{ $inventory->id }}"
                                                class="form-check-input inventory-checkbox" form="report-export-form">

                                        </td>


                                        <td>

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            {{ $inventory->nomor_inventaris }}

                                        </td>


                                        <td>

                                            {{ $inventory->masterSaranaInventaris?->nama_sarana_inventaris ?? '-' }}

                                        </td>


                                        <td>

                                            {{ $inventory->user ?: '-' }}

                                        </td>


                                        <td>

                                            {{ $inventory->location?->nama_lokasi ?? '-' }}

                                        </td>


                                        <td>

                                            {{ $inventory->status ?: '-' }}

                                        </td>


                                    </tr>


                                @empty


                                    <tr>

                                        <td colspan="7" class="text-center text-muted">

                                            Tidak ada data inventaris.

                                        </td>

                                    </tr>
                                @endforelse


                            </tbody>


                        </table>


                    </div>


                </div>


            </div>


        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /*
            |--------------------------------------------------------------------------
            | ELEMENT
            |--------------------------------------------------------------------------
            */


            const selectModeBtn =
                document.getElementById('select-mode-btn');


            const finishSelectBtn =
                document.getElementById('finish-select-btn');


            const selectAll =
                document.getElementById('select-all');


            const selectColumns =
                document.querySelectorAll('.select-column');


            const inventoryCheckboxes =
                document.querySelectorAll('.inventory-checkbox');


            const selectAllColumns =
                document.getElementById('select-all-columns');


            const columnCheckboxes =
                document.querySelectorAll('.report-column-checkbox');


            const exportButtons =
                document.getElementById('export-buttons');


            const exportExcelBtn =
                document.getElementById('export-excel-btn');


            const exportPdfBtn =
                document.getElementById('export-pdf-btn');


            let selectMode = false;


            /*
            |--------------------------------------------------------------------------
            | UPDATE MODE PILIH INVENTARIS
            |--------------------------------------------------------------------------
            */


            function updateMode() {


                selectColumns.forEach(function(column) {

                    column.style.display =
                        selectMode ? '' : 'none';

                });


                selectModeBtn.style.display =
                    selectMode ? 'none' : '';


                finishSelectBtn.style.display =
                    selectMode ? '' : 'none';


                exportButtons.style.display =
                    selectMode ? 'flex' : 'none';


                /*
                | Kalau selesai memilih
                */

                if (!selectMode) {


                    inventoryCheckboxes.forEach(function(checkbox) {

                        checkbox.checked = false;

                    });


                    if (selectAll) {

                        selectAll.checked = false;

                    }


                    updateExportButtons();

                }

            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS BUTTON EXPORT
            |--------------------------------------------------------------------------
            */


            function updateExportButtons() {


                const selectedInventories =
                    document.querySelectorAll(
                        '.inventory-checkbox:checked'
                    ).length;


                const selectedColumns =
                    document.querySelectorAll(
                        '.report-column-checkbox:checked'
                    ).length;


                const canExport =
                    selectedInventories > 0 &&
                    selectedColumns > 0;


                exportExcelBtn.disabled = !canExport;


                exportPdfBtn.disabled = !canExport;


            }


            /*
            |--------------------------------------------------------------------------
            | MODE PILIH INVENTARIS
            |--------------------------------------------------------------------------
            */


            selectModeBtn.addEventListener(
                'click',
                function() {


                    selectMode = true;


                    updateMode();


                }
            );


            finishSelectBtn.addEventListener(
                'click',
                function() {


                    selectMode = false;


                    updateMode();


                }
            );


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX HEADER
            |
            | CENTANG = SEMUA INVENTARIS CENTANG
            |--------------------------------------------------------------------------
            */


            if (selectAll) {


                selectAll.addEventListener(
                    'change',
                    function() {


                        inventoryCheckboxes.forEach(
                            function(checkbox) {

                                checkbox.checked =
                                    selectAll.checked;

                            }
                        );


                        updateExportButtons();


                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX INVENTARIS SATUAN
            |--------------------------------------------------------------------------
            */


            inventoryCheckboxes.forEach(
                function(checkbox) {


                    checkbox.addEventListener(
                        'change',
                        function() {


                            const selected =
                                document.querySelectorAll(
                                    '.inventory-checkbox:checked'
                                ).length;


                            if (selectAll) {


                                selectAll.checked =
                                    inventoryCheckboxes.length > 0 &&
                                    selected === inventoryCheckboxes.length;


                                selectAll.indeterminate =
                                    selected > 0 &&
                                    selected < inventoryCheckboxes.length;

                            }


                            updateExportButtons();


                        }
                    );


                }
            );


            /*
            |--------------------------------------------------------------------------
            | PILIH SEMUA KOLOM
            |--------------------------------------------------------------------------
            */


            if (selectAllColumns) {


                selectAllColumns.addEventListener(
                    'change',
                    function() {


                        columnCheckboxes.forEach(
                            function(checkbox) {

                                checkbox.checked =
                                    selectAllColumns.checked;

                            }
                        );


                        updateExportButtons();


                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX KOLOM SATUAN
            |--------------------------------------------------------------------------
            */


            columnCheckboxes.forEach(
                function(checkbox) {


                    checkbox.addEventListener(
                        'change',
                        function() {


                            const selected =
                                document.querySelectorAll(
                                    '.report-column-checkbox:checked'
                                ).length;


                            selectAllColumns.checked =
                                columnCheckboxes.length > 0 &&
                                selected === columnCheckboxes.length;


                            selectAllColumns.indeterminate =
                                selected > 0 &&
                                selected < columnCheckboxes.length;


                            updateExportButtons();


                        }
                    );


                }
            );


            /*
            |--------------------------------------------------------------------------
            | INITIAL
            |--------------------------------------------------------------------------
            */


            updateMode();

            updateExportButtons();


        });
    </script>
@endpush
