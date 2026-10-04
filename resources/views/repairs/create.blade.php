@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <div class="page-header d-print-none mb-3">

            <div class="row align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Tambah Perbaikan
                    </h2>

                    <div class="text-secondary">
                        Tambahkan riwayat perbaikan sarana inventaris.
                    </div>

                </div>

                <div class="col-auto">

                    <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-secondary">

                        <i class="ti ti-arrow-left me-1"></i>

                        Kembali

                    </a>

                </div>

            </div>

        </div>


        <div class="card">

            <div class="card-body">

                <form action="{{ route('repairs.store', $inventory) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    @include('repairs.form')

                </form>

            </div>

        </div>

    </div>
@endsection
