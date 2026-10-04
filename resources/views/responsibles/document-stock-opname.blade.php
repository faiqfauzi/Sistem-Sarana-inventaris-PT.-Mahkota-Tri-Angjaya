<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Document Stock Opname
    </title>


    <style>
        * {
            box-sizing: border-box;
        }


        @page {
            margin: 20px 15px;
        }


        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #000;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {
            margin-bottom: 20px;
        }


        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }


        .subtitle {
            text-align: center;
            font-size: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMASI PENANGGUNG JAWAB
        |--------------------------------------------------------------------------
        */

        .responsible-info {
            margin-bottom: 15px;
        }


        .responsible-info table {
            width: 100%;
            border-collapse: collapse;
        }


        .responsible-info td {
            padding: 3px 0;
        }


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }


        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }


        table.data-table th {
            text-align: center;
            font-weight: bold;
        }


        table.data-table td {
            word-wrap: break-word;
        }


        .text-center {
            text-align: center;
        }


        .foto {
            width: 55px;
            height: 55px;
            object-fit: cover;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 15px;
            font-size: 8px;
        }
    </style>

</head>


<body>


    {{-- HEADER --}}

    <div class="header">

        <div class="title">

            DOKUMEN STOCK OPNAME

        </div>


        <div class="subtitle">

            Sistem Sarana Inventaris

        </div>

    </div>



    {{-- INFORMASI PENANGGUNG JAWAB --}}

    <div class="responsible-info">

        <table>

            <tr>

                <td width="150">

                    <strong>Penanggung Jawab</strong>

                </td>


                <td>

                    : {{ $responsible->nama_penanggung_jawab }}

                </td>

            </tr>

        </table>

    </div>



    {{-- TABEL --}}

    <table class="data-table">

        <thead>

            <tr>

                <th width="3%">
                    No
                </th>

                <th width="8%">
                    Nomor Inventaris
                </th>

                <th width="10%">
                    Sarana Inventaris
                </th>

                <th width="7%">
                    User
                </th>

                <th width="8%">
                    Lokasi
                </th>

                <th width="10%">
                    Spesifikasi
                </th>

                <th width="7%">
                    Tanggal Perolehan
                </th>

                <th width="6%">
                    Status
                </th>

                <th width="8%">
                    Tanggal Stock Opname
                </th>

                <th width="6%">
                    Kondisi
                </th>

                <th width="7%">
                    Auditor
                </th>

                <th width="10%">
                    Foto
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse ($stockOpnames as $index => $stockOpname)
                <tr>

                    {{-- NO --}}

                    <td class="text-center">

                        {{ $index + 1 }}

                    </td>


                    {{-- NOMOR INVENTARIS --}}

                    <td>

                        {{ $stockOpname->inventory->nomor_inventaris ?? '-' }}

                    </td>


                    {{-- SARANA INVENTARIS --}}

                    <td>

                        {{ $stockOpname->inventory->masterSaranaInventaris->nama_sarana_inventaris ?? '-' }}

                    </td>


                    {{-- USER --}}

                    <td>

                        {{ $stockOpname->inventory->user ?? '-' }}

                    </td>


                    {{-- LOKASI --}}

                    <td>

                        {{ $stockOpname->inventory->location->nama_lokasi ?? '-' }}

                    </td>


                    {{-- SPESIFIKASI --}}

                    <td>

                        {{ $stockOpname->inventory->spesifikasi ?? '-' }}

                    </td>


                    {{-- TANGGAL PEROLEHAN --}}

                    <td class="text-center">

                        {{ $stockOpname->inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}

                    </td>


                    {{-- STATUS --}}

                    <td>

                        {{ $stockOpname->inventory->status ?? '-' }}

                    </td>


                    {{-- TANGGAL STOCK OPNAME --}}

                    <td class="text-center">

                        {{ $stockOpname->tanggal?->format('d-m-Y') ?? '-' }}

                    </td>


                    {{-- KONDISI --}}

                    <td>

                        {{ $stockOpname->kondisi ?? '-' }}

                    </td>


                    {{-- AUDITOR --}}

                    <td>

                        {{ $stockOpname->auditor ?? '-' }}

                    </td>


                    {{-- FOTO --}}

                    <td class="text-center">

                        @if ($stockOpname->foto)
                            <img src="{{ public_path('storage/' . $stockOpname->foto) }}" class="foto">
                        @else
                            -
                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="12" class="text-center">

                        Belum ada data Stock Opname.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>


    <div class="footer">

        Dicetak pada:
        {{ now()->format('d-m-Y H:i') }}

    </div>


</body>

</html>
