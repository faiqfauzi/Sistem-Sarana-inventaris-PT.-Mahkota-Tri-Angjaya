@extends('layouts.app')

@section('content')

<div class="container-xl">

    <div class="page-header d-print-none mb-3">
        <h2 class="page-title">
            Tambah Lokasi
        </h2>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('locations.store') }}" method="POST">
                @csrf

                @include('locations.form')

            </form>

        </div>
    </div>

</div>

@endsection