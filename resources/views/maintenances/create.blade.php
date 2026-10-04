@extends('layouts.app')

@section('content')

<div class="container-xl">

    <h2 class="page-title mb-3">
        Tambah Perawatan
    </h2>

    <div class="card">
        <div class="card-body">

            <form
                action="{{ route('maintenances.store', $inventory) }}"
                method="POST">

                @csrf

                @include('maintenances.form')

            </form>

        </div>
    </div>

</div>

@endsection