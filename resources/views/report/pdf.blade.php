<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Inventaris
    </title>


    <style>
        @page {

            margin: 20px 15px;

        }


        body {

            font-family: DejaVu Sans, sans-serif;

            font-size: 8px;

            color: #000;

        }


        .header {

            text-align: center;

            margin-bottom: 15px;

        }


        .header h2 {

            margin: 0;

            font-size: 16px;

        }


        .header p {

            margin-top: 5px;

            font-size: 9px;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        th,

        td {

            border: 1px solid #000;

            padding: 5px;

            vertical-align: middle;

        }


        th {

            background-color: #206BC4;

            color: #FFFFFF;

            text-align: center;

            font-weight: bold;

        }


        td.no {

            text-align: center;

            width: 25px;

        }


        td {

            word-wrap: break-word;

        }


        .foto {

            width: 70px;

            height: 70px;

            object-fit: contain;

        }


        .empty {

            text-align: center;

            padding: 20px;

        }


        .footer {

            margin-top: 15px;

            font-size: 8px;

        }
    </style>

</head>


<body>


    {{-- ================================================================
    | JUDUL LAPORAN
    ================================================================= --}}

    <div class="header">

        <h2>
            LAPORAN INVENTARIS
        </h2>


        <p>

            Tanggal Cetak:

            {{ now()->format('d-m-Y H:i') }}

        </p>


    </div>


    {{-- ================================================================
    | HELPER FOTO
    ================================================================= --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Mengubah foto menjadi Base64
        |
        | Ini lebih aman untuk DomPDF karena gambar dari storage/public
        | bisa langsung dimasukkan ke dalam PDF.
        |--------------------------------------------------------------------------
        */

        $getImageBase64 = function ($foto) {
            if (!$foto) {
                return null;
            }

            $path = storage_path('app/public/' . $foto);

            if (!file_exists($path)) {
                return null;
            }

            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            $mime = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',

                'png' => 'image/png',

                default => null,
            };

            if (!$mime) {
                return null;
            }

            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        };

    @endphp


    {{-- ================================================================
    | TABEL LAPORAN
    ================================================================= --}}

    <table>


        {{-- ============================================================
        | HEADER
        ============================================================= --}}

        <thead>

            <tr>


                {{-- NO SELALU ADA --}}

                <th>
                    No
                </th>


                {{-- HEADER SESUAI KOLOM PILIHAN --}}

                @foreach ($columns as $column)
                    @if (isset($columnLabels[$column]))
                        <th>

                            {{ $columnLabels[$column] }}

                        </th>
                    @endif
                @endforeach


            </tr>

        </thead>


        {{-- ============================================================
        | ISI TABEL
        ============================================================= --}}

        <tbody>


            @forelse ($inventories as $index => $inventory)


                {{-- ====================================================
                | AMBIL RIWAYAT TERBARU
                ===================================================== --}}

                @php

                    $maintenance = $inventory->maintenances->first();

                    $repair = $inventory->repairs->first();

                    $stockOpname = $inventory->stockOpnames->first();

                @endphp


                <tr>


                    {{-- =================================================
                    | NOMOR
                    ================================================== --}}

                    <td class="no">

                        {{ $index + 1 }}

                    </td>


                    {{-- =================================================
                    | KOLOM DINAMIS
                    ================================================== --}}

                    @foreach ($columns as $column)
                        {{-- =============================================
                        | NOMOR INVENTARIS
                        ============================================== --}}

                        @if ($column === 'nomor_inventaris')
                            <td>

                                {{ $inventory->nomor_inventaris ?: '-' }}

                            </td>


                            {{-- =============================================
                        | TANGGAL PEROLEHAN
                        ============================================== --}}
                        @elseif ($column === 'tanggal_perolehan')
                            <td>

                                {{ $inventory->tanggal_perolehan?->format('d-m-Y') ?: '-' }}

                            </td>


                            {{-- =============================================
                        | SARANA INVENTARIS
                        ============================================== --}}
                        @elseif ($column === 'sarana_inventaris')
                            <td>

                                {{ $inventory->masterSaranaInventaris?->nama_sarana_inventaris ?: '-' }}

                            </td>


                            {{-- =============================================
                        | FOTO INVENTARIS
                        ============================================== --}}
                        @elseif ($column === 'foto')
                            <td style="text-align: center;">

                                @php

                                    $image = $getImageBase64($inventory->foto);

                                @endphp


                                @if ($image)
                                    <img src="{{ $image }}" class="foto" alt="Foto Inventaris">
                                @else
                                    -
                                @endif

                            </td>


                            {{-- =============================================
                        | SPESIFIKASI
                        ============================================== --}}
                        @elseif ($column === 'spesifikasi')
                            <td>

                                {{ $inventory->spesifikasi ?: '-' }}

                            </td>


                            {{-- =============================================
                        | LOKASI
                        ============================================== --}}
                        @elseif ($column === 'lokasi')
                            <td>

                                {{ $inventory->location?->nama_lokasi ?: '-' }}

                            </td>


                            {{-- =============================================
                        | USER
                        ============================================== --}}
                        @elseif ($column === 'user')
                            <td>

                                {{ $inventory->user ?: '-' }}

                            </td>


                            {{-- =============================================
                        | PENANGGUNG JAWAB
                        ============================================== --}}
                        @elseif ($column === 'penanggung_jawab')
                            <td>

                                {{ $inventory->responsible?->nama_penanggung_jawab ?: '-' }}

                            </td>


                            {{-- =============================================
                        | TANGGAL PERAWATAN
                        ============================================== --}}
                        @elseif ($column === 'tanggal_perawatan')
                            <td>

                                {{ $maintenance?->tanggal_perawatan?->format('d-m-Y') ?: '-' }}

                            </td>


                            {{-- =============================================
                        | JENIS PERAWATAN
                        ============================================== --}}
                        @elseif ($column === 'jenis_perawatan')
                            <td>

                                {{ $maintenance?->jenis_perawatan ?: '-' }}

                            </td>


                            {{-- =============================================
                        | TANGGAL PERBAIKAN
                        ============================================== --}}
                        @elseif ($column === 'tanggal_perbaikan')
                            <td>

                                {{ $repair?->tanggal_pengajuan?->format('d-m-Y') ?: '-' }}

                            </td>


                            {{-- =============================================
                        | JENIS PERBAIKAN
                        ============================================== --}}
                        @elseif ($column === 'jenis_perbaikan')
                            <td>

                                {{ $repair?->jenis_perbaikan ?: '-' }}

                            </td>


                            {{-- =============================================
                        | FOTO STOCK OPNAME
                        ============================================== --}}
                        @elseif ($column === 'foto_so')
                            <td style="text-align: center;">

                                @php

                                    $image = $getImageBase64($stockOpname?->foto);

                                @endphp


                                @if ($image)
                                    <img src="{{ $image }}" class="foto" alt="Foto Stock Opname">
                                @else
                                    -
                                @endif

                            </td>


                            {{-- =============================================
                        | TANGGAL STOCK OPNAME
                        ============================================== --}}
                        @elseif ($column === 'tanggal_so')
                            <td>

                                {{ $stockOpname?->tanggal?->format('d-m-Y') ?: '-' }}

                            </td>


                            {{-- =============================================
                        | STATUS / KONDISI
                        ============================================== --}}
                        @elseif ($column === 'status_kondisi')
                            <td>

                                {{ $stockOpname?->kondisi ?: ($inventory->status ?: '-') }}

                            </td>
                        @endif
                    @endforeach


                </tr>


            @empty


                <tr>

                    <td colspan="{{ count($columns) + 1 }}" class="empty">

                        Tidak ada data inventaris.

                    </td>

                </tr>


            @endforelse


        </tbody>


    </table>


    {{-- ================================================================
    | FOOTER
    ================================================================= --}}

    <div class="footer">

        Total Inventaris:

        {{ $inventories->count() }}

    </div>


</body>

</html>
