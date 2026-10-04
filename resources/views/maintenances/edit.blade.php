@extends('layouts.app')

@section('content')

<div class="container-xl">

    <h2 class="page-title mb-3">
        Edit Perawatan
    </h2>

    <div class="card">

        <div class="card-body">

            <form
                action="{{ route('maintenances.update', $maintenance) }}"
                method="POST">

                @csrf
                @method('PUT')

                @include('maintenances.form')

            </form>

        </div>

    </div>

</div>

@endsection