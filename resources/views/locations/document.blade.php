<!DOCTYPE html>

<html lang="id">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>
        Dokumen Lokasi - {{ $location->nama_lokasi }}
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
            width: 100px;
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
           SUMMARY TABLE
        ======================================== */

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .summary-table th {
            text-align: center;
            font-weight: bold;
            background: #f5f5f5;
        }


        /* ========================================
           INVENTORY TABLE
        ======================================== */

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th,
        .inventory-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .inventory-table th {
            text-align: center;
            font-weight: bold;
            background: #f5f5f5;
        }


        /* ========================================
           UTILITY
        ======================================== */

        .text-center {
            text-align: center;
        }

        .empty {
            text-align: center;
            padding: 12px !important;
        }


        /* ========================================
           FOOTER
        ======================================== */

        .footer {
            margin-top: 25px;
            font-size: 9px;
            color: #666;
            text-align: right;
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

                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}">
                    @endif

                </td>


                <td class="company-name">

                    <h1>
                        DOKUMEN LOKASI INVENTARIS
                    </h1>

                    <p>
                        Sistem Informasi Inventaris
                    </p>

                </td>


                <td style="width: 100px;">
                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================
         JUDUL DOKUMEN
    ======================================== --}}

    <div class="document-title">

        <h2>
            DAFTAR SARANA INVENTARIS BERDASARKAN LOKASI
        </h2>

    </div>



    {{-- ========================================
         INFORMASI LOKASI
    ======================================== --}}

    <div class="section">

        <div class="section-title">

            INFORMASI LOKASI

        </div>


        <table class="info-table">

            <tr>

                <td class="info-label">
                    Lokasi
                </td>

                <td>
                    {{ $location->nama_lokasi }}
                </td>

            </tr>


            @if ($location->keterangan)
                <tr>

                    <td class="info-label">
                        Keterangan
                    </td>

                    <td>
                        {{ $location->keterangan }}
                    </td>

                </tr>
            @endif


            <tr>

                <td class="info-label">
                    Total Sarana Inventaris
                </td>

                <td>
                    {{ $inventories->count() }}
                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================
         RINGKASAN SARANA INVENTARIS
    ======================================== --}}

    <div class="section">

        <div class="section-title">

            RINGKASAN SARANA INVENTARIS

        </div>


        <table class="summary-table">

            <thead>

                <tr>

                    <th width="45">
                        No
                    </th>

                    <th>
                        Sarana Inventaris
                    </th>

                    <th width="100">
                        Jumlah
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($summary as $index => $item)
                    <tr>

                        <td class="text-center">
                            {{ $index + 1 }}
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

                        <td colspan="3" class="empty">
                            Tidak ada sarana inventaris.
                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ========================================
         DAFTAR SARANA INVENTARIS
    ======================================== --}}

    <div class="section">

        <div class="section-title">

            DAFTAR SARANA INVENTARIS

        </div>


        <table class="inventory-table">

            <thead>

                <tr>

                    <th width="35">
                        No
                    </th>

                    <th width="90">
                        Nomor Inventaris
                    </th>

                    <th width="110">
                        Sarana Inventaris
                    </th>

                    <th width="70">
                        User
                    </th>

                    <th width="100">
                        Penanggung Jawab
                    </th>

                    <th>
                        Spesifikasi
                    </th>

                    <th width="80">
                        Tanggal Perolehan
                    </th>

                    <th width="70">
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($inventories as $index => $inventory)
                    <tr>

                        {{-- NO --}}
                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>


                        {{-- NOMOR INVENTARIS --}}
                        <td>
                            {{ $inventory->nomor_inventaris }}
                        </td>


                        {{-- SARANA INVENTARIS --}}
                        <td>
                            {{ $inventory->masterSaranaInventaris?->nama_sarana_inventaris ?? '-' }}
                        </td>


                        {{-- USER --}}
                        <td>
                            {{ $inventory->user ?: '-' }}
                        </td>


                        {{-- PENANGGUNG JAWAB --}}
                        <td>
                            {{ $inventory->responsible?->nama_penanggung_jawab ?? '-' }}
                        </td>


                        {{-- SPESIFIKASI --}}
                        <td>
                            {{ $inventory->spesifikasi ?: '-' }}
                        </td>


                        {{-- TANGGAL PEROLEHAN --}}
                        <td class="text-center">
                            {{ $inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}
                        </td>


                        {{-- STATUS --}}
                        <td class="text-center">
                            {{ $inventory->status ?: '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="empty">

                            Tidak ada sarana inventaris di lokasi ini.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ========================================
         FOOTER
    ======================================== --}}

    


</body>

</html>
