@extends('layouts.app')

@section('content')
    @php

        $user = auth()->user();

        $isAdmin = $user && $user->role === 'admin';

        $isOwner = $user && $user->responsible_id && $inventory->responsible_id === $user->responsible_id;

        $canManageHistory = $isAdmin || $isOwner;

    @endphp
    <div class="container-xl">

        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">

                <div class="col">
                    <h2 class="page-title">
                        Detail Sarana Inventaris
                    </h2>
                </div>

                <div class="col-auto">

                    <a href="{{ route('inventories.qr', $inventory) }}" class="btn btn-primary">

                        QR Code

                    </a>

                    @if ($canManageHistory)
                        <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-warning">
                            Edit
                        </a>
                    @endif

                    <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </div>

            </div>
        </div>

        {{-- Inventory Inventaris --}}
        <div class="card">

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
                        <td>{{ $inventory->masterSaranaInventaris->nama_sarana_inventaris }}</td>
                    </tr>

                    <tr>
                        <th>User</th>
                        <td>{{ $inventory->user }}</td>
                    </tr>

                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $inventory->location->nama_lokasi }}</td>
                    </tr>

                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $inventory->responsible->nama_penanggung_jawab }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{{ $inventory->status }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Perolehan</th>
                        <td>{{ $inventory->tanggal_perolehan?->format('d-m-Y') ?? '-' }}</td>
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

        <div class="container-xl">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">

                <div></div>

                @if ($isAdmin)
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editHeaderModal">

                        <i class="ti ti-photo me-1"></i>
                        Edit Gambar

                    </button>
                @endif

            </div>

            <div class="master-kop-wrapper">

                @php
                    $headerImage = null;

                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                        $path = 'storage/images/daftar-sarana-inventaris.' . $extension;

                        if (file_exists(public_path($path))) {
                            $headerImage = asset($path);
                            break;
                        }
                    }
                @endphp

                @if ($headerImage)
                    <img src="{{ $headerImage }}" alt="Kop Perusahaan" class="master-kop">
                @else
                    <img src="{{ asset('images/daftar-sarana-inventaris.png') }}" alt="Kop Perusahaan" class="master-kop">
                @endif

            </div>

        </div>


        <div class="card mt-4">
            {{-- Riwayat Perawatan --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="card-title">
                    Riwayat Perawatan
                </h3>

                <div class="d-flex gap-2">

                    @if ($canManageHistory)
                        <a href="{{ route('maintenances.create', $inventory) }}" class="btn btn-primary btn-sm">

                            Tambah

                        </a>
                    @endif

                    <a href="{{ route('inventories.maintenances', $inventory) }}" class="btn btn-secondary btn-sm">

                        Detail

                    </a>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Tanggal</th>
                            <th>Perawatan Selanjutnya</th>
                            <th>Jenis Perawatan</th>
                            <th>Pelaksana</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($maintenances as $maintenance)
                            <tr>

                                <td>
                                    {{ $maintenance->tanggal_perawatan?->format('d-m-Y') }}
                                </td>


                                <td>
                                    {{ $maintenance->tanggal_perawatan_selanjutnya?->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $maintenance->jenis_perawatan }}
                                </td>

                                <td>
                                    {{ $maintenance->pelaksana }}
                                </td>

                                <td>

                                    @if ($canManageHistory)
                                        <a href="{{ route('maintenances.edit', $maintenance) }}"
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
                                        <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus perawatan ini?')"
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

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    Belum ada riwayat perawatan

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        <div class="card mt-4">

            {{-- Riwayat Perbaikan --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="card-title">
                    Riwayat Perbaikan
                </h3>

                <div class="d-flex gap-2">

                    @if ($canManageHistory)
                        <a href="{{ route('repairs.create', $inventory) }}" class="btn btn-primary btn-sm">

                            Tambah

                        </a>
                    @endif

                    <a href="{{ route('inventories.repairs', $inventory) }}" class="btn btn-secondary btn-sm">

                        Detail

                    </a>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Tindak Lanjut</th>
                            <th>Jenis Perbaikan</th>
                            <th>Pelaksana</th>
                            <th>Dokumen</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($repairs as $repair)
                            <tr>

                                {{-- TANGGAL PENGAJUAN --}}
                                <td>
                                    {{ $repair->tanggal_pengajuan?->format('d-m-Y') ?? '-' }}
                                </td>


                                {{-- TANGGAL TINDAK LANJUT --}}
                                <td>
                                    {{ $repair->tanggal_tindak_lanjut?->format('d-m-Y') ?? '-' }}
                                </td>


                                {{-- JENIS PERBAIKAN --}}
                                <td>
                                    {{ $repair->jenis_perbaikan }}
                                </td>


                                {{-- PELAKSANA --}}
                                <td>
                                    {{ $repair->pelaksana }}
                                </td>


                                {{-- DOKUMEN --}}
                                <td>

                                    @if ($repair->dokumen)
                                        <a href="{{ asset('storage/' . $repair->dokumen) }}" target="_blank"
                                            class="btn btn-primary btn-sm">

                                            <i class="ti ti-file"></i>

                                            Lihat Dokumen

                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    {{-- EDIT --}}
                                    @if ($canManageHistory)
                                        <a href="{{ route('repairs.edit', $repair) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit" aria-label="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 20h9" />

                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                            </svg>

                                        </a>
                                    @endif


                                    {{-- HAPUS --}}
                                    @if ($isAdmin)
                                        <form action="{{ route('repairs.destroy', $repair) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus perbaikan ini?')"
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

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-4">

                                    Belum ada riwayat perbaikan.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        <div class="card mt-4">

            {{-- Riwayat Stok Opname --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="card-title">
                    Riwayat Stok Opname
                </h3>

                <div class="d-flex gap-2">

                    @if ($canManageHistory)
                        <a href="{{ route('stock-opnames.create', $inventory) }}" class="btn btn-primary btn-sm">

                            Tambah

                        </a>
                    @endif

                    <a href="{{ route('inventories.stock-opnames', $inventory) }}" class="btn btn-secondary btn-sm">

                        Detail

                    </a>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Tanggal</th>
                            <th>Kondisi</th>
                            <th>Auditor</th>
                            <th>Foto</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($stockOpnames as $stockOpname)
                            <tr>

                                <td>
                                    {{ $stockOpname->tanggal?->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $stockOpname->kondisi }}
                                </td>

                                <td>
                                    {{ $stockOpname->auditor }}
                                </td>
                                <td>
                                    @if ($stockOpname->foto)
                                        <img src="{{ asset('storage/' . $stockOpname->foto) }}" width="70"
                                            class="img-thumbnail">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>

                                    @if ($canManageHistory)
                                        <a href="{{ route('stock-opnames.edit', $stockOpname) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                                            </svg>

                                        </a>
                                    @endif


                                    @if ($isAdmin)
                                        <form action="{{ route('stock-opnames.destroy', $stockOpname) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus stok opname ini?')"
                                                class="btn btn-danger btn-sm btn-icon">

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

                        @empty

                            <tr>

                                <td colspan="6" class="text-center">

                                    Belum ada riwayat stok opname

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        <div class="card mt-4">

            {{-- Riwayat Mutasi --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h3 class="card-title">
                    Riwayat Pemindahan & Pemusnahan
                </h3>

                <div class="d-flex gap-2">

                    @if ($canManageHistory)
                        <a href="{{ route('mutations.create', $inventory) }}" class="btn btn-primary btn-sm">

                            Tambah

                        </a>
                    @endif

                    <a href="{{ route('inventories.mutations', $inventory) }}" class="btn btn-secondary btn-sm">

                        Detail

                    </a>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

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
                                    {{ $mutation->tanggal?->format('d-m-Y') }}
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

                                            <i class="ti ti-file"></i>

                                            Lihat Dokumen

                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>

                                    @if ($canManageHistory)
                                        <a href="{{ route('mutations.edit', $mutation) }}"
                                            class="btn btn-warning btn-sm btn-icon" title="Edit">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

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

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus mutasi ini?')"
                                                class="btn btn-danger btn-sm btn-icon">

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

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    Belum ada riwayat mutasi

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        @if ($isAdmin)
            <div class="modal fade" id="editHeaderModal" tabindex="-1" aria-labelledby="editHeaderModalLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content">

                        <form method="POST" action="{{ route('master-sarana-inventaris.update-header') }}"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="modal-header">

                                <h5 class="modal-title" id="editHeaderModalLabel">
                                    Edit Gambar Header
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>

                            </div>

                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Pilih Gambar Baru
                                    </label>

                                    <input type="file" name="header" class="form-control"
                                        accept=".jpg,.jpeg,.png,.webp" required>

                                    <div class="form-text">
                                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Simpan
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        @endif
    @endsection
