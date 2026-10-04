@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="auth-container">

        {{-- LOGO & JUDUL SISTEM --}}
        <div class="text-center mb-4">

            <div class="auth-logo-wrapper">

                <img src="{{ asset('images/login.jpeg') }}" alt="Logo Perusahaan" class="auth-logo">

            </div>

            <h1 class="auth-system-title">
                Sistem Sarana Inventaris
            </h1>

        </div>


        {{-- CARD LOGIN --}}
        <div class="card auth-card">

            <div class="card-header">
                <h3 class="mb-0">
                    Masuk
                </h3>
            </div>

            <div class="card-body">

                @if ($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    {{-- USERNAME --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required
                            autofocus>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input type="password" name="password" class="form-control" required>

                    </div>


                    {{-- BUTTON --}}
                    <button type="submit" class="btn btn-primary w-100">

                        <i class="ti ti-login me-2"></i>

                        Masuk

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
