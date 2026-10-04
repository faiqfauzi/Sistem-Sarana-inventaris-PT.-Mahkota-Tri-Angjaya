@extends('layouts.app')

@section('content')

<div class="container-xl">

    <h2 class="page-title mb-3">
        Edit Riwayat Pemindahan / Pemusnahan
    </h2>

    <div class="card">

        <div class="card-body">

            <form
                action="{{ route('mutations.update', $mutation) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('mutations.form')

            </form>

        </div>

    </div>

</div>

@endsection