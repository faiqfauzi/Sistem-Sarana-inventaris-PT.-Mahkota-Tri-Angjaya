@extends('layouts.app')

@section('content')

<div class="container-xl">

    <div class="page-header d-print-none mb-3">
        <h2 class="page-title">
            Edit Lokasi
        </h2>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('locations.update', $location) }}" method="POST">

                @csrf
                @method('PUT')

                @include('locations.form')

            </form>

        </div>
    </div>

</div>

@endsection