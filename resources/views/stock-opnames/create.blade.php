@extends('layouts.app')

@section('content')

<div class="container-xl">

    <h2 class="page-title mb-3">
        Tambah Stok Opname
    </h2>

    <div class="card">
        <div class="card-body">

            <form
                action="{{ route('stock-opnames.store', $inventory) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                @include('stock-opnames.form')

            </form>

        </div>
    </div>

</div>

@endsection