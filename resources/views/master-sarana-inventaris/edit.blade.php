@extends('layouts.app')

@section('content')

<div class="container-xl">

    <div class="page-header d-print-none mb-3">
        <h2 class="page-title">
            Edit Sarana
        </h2>
    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('master-sarana-inventaris.update',$masterSaranaInventaris) }}" method="POST">

                @csrf
                @method('PUT')

                @include('master-sarana-inventaris.form')

            </form>

        </div>

    </div>

</div>

@endsection