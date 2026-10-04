@extends('layouts.app')

@section('content')

<div class="container-xl">

    <div class="page-header d-print-none mb-3">
        <h2 class="page-title">
            Tambah Inventaris
        </h2>
    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('inventories.store') }}"
            method="POST"
            enctype="multipart/form-data">

                @csrf

                @include('inventories.form')

            </form>

        </div>

    </div>

</div>

@endsection