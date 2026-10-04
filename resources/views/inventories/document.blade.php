<!DOCTYPE html>

<html lang="id">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>
        Dokumen Sarana Inventaris
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }


        /* ========================================
       HEADER
    ======================================== */

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .company-logo {
            width: 70px;
            text-align: center;
        }

        .company-logo img {
            width: 150px;
            height: auto;
        }

        .company-name {
            text-align: center;
        }

        .company-name h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .company-name p {
            margin: 3px 0 0;
            font-size: 9px;
        }


        /* ========================================
       TITLE
    ======================================== */

        .document-title {
            text-align: center;
            margin: 15px 0 20px;
        }

        .document-title h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }


        /* ========================================
       SECTION
    ======================================== */

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #e9ecef;
            border: 1px solid #000;

            padding: 7px 10px;

            font-size: 11px;
            font-weight: bold;

            margin-bottom: 0;
        }


        /* ========================================
       INFORMATION TABLE
    ======================================== */

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 7px;
            vertical-align: top;
        }

        .info-label {
            width: 30%;
            font-weight: bold;
            background: #f5f5f5;
        }


        /* ========================================
       HISTORY TABLE
    ======================================== */

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th,
        .history-table td {

            border: 1px solid #000;

            padding: 6px;

            vertical-align: top;
        }

        .history-table th {

            text-align: center;

            font-weight: bold;

            background: #f5f5f5;
        }

        .empty {
            text-align: center;
            padding: 12px !important;
        }


        /* ========================================
       PAGE BREAK
    ======================================== */

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    {{-- ========================================
     HEADER
======================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="company-logo">

                    {{-- GANTI PATH LOGO JIKA DIPERLUKAN --}}

                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}">
                    @endif

                </td>


                <td class="company-name">

                    <h1>
                        DOKUMEN SARANA INVENTARIS
                    </h1>

                    <p>
                        Sistem Informasi Inventaris
                    </p>

                </td>


                <td style="width: 70px;">
                </td>

            </tr>

        </table>

    </div>


    {{-- ========================================
     INFORMASI INVENTARIS
======================================== --}}

    <div class="section">

        <div class="section-title">

            INFORMASI SARANA INVENTARIS

        </div>


        <table class="info-table">

            <tr>

                <td class="info-label">
                    Nomor Inventaris
                </td>

                <td>
                    {{ $inventory->nomor_inventaris }}
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Sarana Inventaris
                </td>

                <td>
                    {{ $inventory->masterSaranaInventaris->nama_sarana_inventaris ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    User
                </td>

                <td>
                    {{ $inventory->user ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="info-label">
                    Lokasi
                </td>

                <td>
                    {{ $inventory->location->nama_lokasi ?? '-' }}
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Penanggung Jawab
                </td>

                <td>
                    {{ $inventory->responsible->nama_penanggung_jawab ?? '-' }}
                </td>

            </tr>

            <tr>
                <td class="info-label">
                    Status
                </td>

                <td>
                    {{ $inventory->status ?? '-' }}
                </td>
            </tr>

            <tr>

                <td class="info-label">
                    Tanggal Perolehan
                </td>

                <td>
                    {{ $inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}
                </td>

            </tr>

            <tr>
                <td class="info-label">
                    Foto
                </td>

                <td style="text-align: center;">

                    @if ($inventory->foto && file_exists(storage_path('app/public/' . $inventory->foto)))
                        <img src="{{ storage_path('app/public/' . $inventory->foto) }}"
                            style="
                    width: 180px;
                    height: auto;
                    max-height: 180px;
                ">
                    @else
                        -
                    @endif

                </td>
            </tr>

            <tr>

                <td class="info-label">
                    Spesifikasi
                </td>

                <td>
                    {{ $inventory->spesifikasi ?? '-' }}
                </td>

            </tr>


        </table>

    </div>



    {{-- ========================================
     RIWAYAT PERAWATAN
======================================== --}}

    <div class="section">

        <div class="section-title">

            RIWAYAT PERAWATAN

        </div>


        <table class="history-table">

            <thead>
                <tr>
                    <th width="40">No</th>
                    <th>Tanggal</th>
                    <th>Perawatan Selanjutnya</th>
                    <th>Jenis Perawatan</th>
                    <th>Pelaksana</th>
                </tr>
            </thead>


            <tbody>

                @forelse($maintenances as $maintenance)
                    <tr>

                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $maintenance->tanggal_perawatan?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $maintenance->tanggal_perawatan_selanjutnya?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $maintenance->jenis_perawatan ?? '-' }}
                        </td>

                        <td>
                            {{ $maintenance->pelaksana ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="empty">

                            Belum ada riwayat perawatan

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ========================================
     RIWAYAT PERBAIKAN
======================================== --}}

    <div class="section">

        <div class="section-title">

            RIWAYAT PERBAIKAN

        </div>


        <table class="history-table">

            <thead>

                <tr>

                    <th width="40">
                        No
                    </th>

                    <th>
                        Tanggal Pengajuan
                    </th>

                    <th>
                        Tanggal Tindak Lanjut
                    </th>

                    <th>
                        Jenis Perbaikan
                    </th>

                    <th>
                        Pelaksana
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($repairs as $repair)
                    <tr>

                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $repair->tanggal_pengajuan?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $repair->tanggal_tindak_lanjut?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $repair->jenis_perbaikan ?? '-' }}
                        </td>

                        <td>
                            {{ $repair->pelaksana ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="empty">

                            Belum ada riwayat perbaikan

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ========================================
     RIWAYAT STOCK OPNAME
======================================== --}}

    <div class="section">

        <div class="section-title">

            RIWAYAT STOK OPNAME

        </div>


        <table class="history-table">

            <thead>
                <tr>

                    <th width="40">
                        No
                    </th>

                    <th>
                        Tanggal
                    </th>

                    <th>
                        Kondisi
                    </th>

                </tr>
            </thead>


            <tbody>

                @forelse($stockOpnames as $stockOpname)
                    <tr>

                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $stockOpname->tanggal?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $stockOpname->kondisi ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="empty">

                            Belum ada riwayat stock opname

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ========================================
     RIWAYAT MUTASI
======================================== --}}

    {{-- <div class="section">

        <div class="section-title">

            RIWAYAT MUTASI

        </div>


        <table class="history-table">

            <thead>

                <tr>

                    <th width="40">
                        No
                    </th>

                    <th>
                        Tanggal
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($mutations as $mutation)
                    <tr>

                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $mutation->tanggal?->format('d-m-Y') ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="2" class="empty">

                            Belum ada riwayat mutasi

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div> --}}

</body>

</html>
