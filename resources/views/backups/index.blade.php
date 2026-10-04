@extends('layouts.app')

@section('title', 'Backup & Restore Database')

@section('content')

    <div class="page-header d-print-none mb-3">

        <div class="container-xl">

            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Backup & Restore Database
                    </h2>

                    <div class="text-secondary">
                        Kelola cadangan database Sistem Sarana Inventaris
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="container-xl">


        {{-- ========================================
            NOTIFIKASI BERHASIL
        ======================================== --}}
        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif


        {{-- ========================================
            NOTIFIKASI ERROR
        ======================================== --}}
        @if (session('error'))
            <div class="alert alert-danger">

                {{ session('error') }}

            </div>
        @endif


        <div class="row row-deck row-cards">


            {{-- ========================================
                BACKUP DATABASE
            ======================================== --}}
            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">

                            Backup Database

                        </h3>

                    </div>


                    <div class="card-body">

                        <p class="text-secondary">

                            Buat salinan database terbaru Sistem Sarana Inventaris.

                        </p>


                        <p class="text-secondary mb-4">

                            File backup akan diunduh dalam format
                            <strong>.sql</strong>.

                        </p>


                        <a href="{{ route('backups.download') }}" class="btn btn-primary">

                            <i class="ti ti-download me-2"></i>

                            Download Backup

                        </a>

                    </div>

                </div>

            </div>



            {{-- ========================================
                RESTORE DATABASE
            ======================================== --}}
            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">

                            Restore Database

                        </h3>

                    </div>


                    <form method="POST" action="{{ route('backups.restore') }}" enctype="multipart/form-data">

                        @csrf


                        <div class="card-body">


                            <div class="alert alert-warning">

                                <div class="d-flex">

                                    <div>

                                        <i class="ti ti-alert-triangle me-2"></i>

                                    </div>


                                    <div>

                                        <strong>Perhatian!</strong>

                                        <div class="mt-1">

                                            Restore database dapat mengganti data
                                            yang sedang digunakan saat ini.

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- FILE --}}
                            <div class="mb-3">

                                <label class="form-label">

                                    Pilih File Backup

                                </label>


                                <input type="file" name="backup_file"
                                    class="form-control @error('backup_file') is-invalid @enderror" accept=".sql" required>


                                @error('backup_file')
                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </div>



                            {{-- KONFIRMASI --}}
                            <label class="form-check">

                                <input type="checkbox" name="confirmation" value="1" class="form-check-input" required>

                                <span class="form-check-label">

                                    Saya memahami bahwa restore akan mengganti
                                    database yang sedang digunakan.

                                </span>

                            </label>


                            @error('confirmation')
                                <div class="text-danger mt-2">

                                    {{ $message }}

                                </div>
                            @enderror


                        </div>


                        <div class="card-footer">

                            <button type="submit" class="btn btn-danger">

                                <i class="ti ti-upload me-2"></i>

                                Restore Database

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
