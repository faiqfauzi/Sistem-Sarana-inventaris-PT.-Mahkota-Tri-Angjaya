@extends('layouts.app')

@section('content')

<div class="container-xl">

    <h2 class="page-title mb-3">
        Edit Stok Opname
    </h2>

    <div class="card">

        <div class="card-body">

            <form
                action="{{ route('stock-opnames.update', $stockOpname) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('stock-opnames.form')

            </form>

        </div>

    </div>

</div>

@endsection