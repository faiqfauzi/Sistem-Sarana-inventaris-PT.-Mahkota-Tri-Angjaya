@extends('layouts.app')

@section('content')

<div class="container-xl">

    <div class="page-header d-print-none mb-3">
        <h2 class="page-title">
            Edit Penanggung Jawab
        </h2>
    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('responsibles.update',$responsible) }}" method="POST">

                @csrf
                @method('PUT')

                @include('responsibles.form')

            </form>

        </div>

    </div>

</div>

@endsection