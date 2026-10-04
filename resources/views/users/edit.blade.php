@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')

    <div class="page-header d-print-none mb-3">

        <div class="container-xl">

            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Edit Akun
                    </h2>

                    <div class="text-secondary">
                        Ubah informasi akun pengguna
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="page-body">

        <div class="container-xl">

            <div class="card">

                <div class="card-body">

                    <form action="{{ route('users.update', $user) }}" method="POST">

                        @csrf
                        @method('PUT')


                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" required>

                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ROLE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Role
                            </label>

                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror"
                                required>

                                <option value="">
                                    Pilih Role
                                </option>


                                <option value="admin" @selected(old('role', $user->role) === 'admin')>
                                    Admin
                                </option>


                                <option value="responsible" @selected(old('role', $user->role) === 'responsible')>
                                    Penanggung Jawab
                                </option>

                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- PENANGGUNG JAWAB --}}
                        <div class="mb-3" id="responsible-wrapper">

                            <label class="form-label">
                                Penanggung Jawab
                            </label>

                            <select name="responsible_id" id="responsible_id"
                                class="form-select @error('responsible_id') is-invalid @enderror">

                                <option value="">
                                    Pilih Penanggung Jawab
                                </option>

                                @foreach ($responsibles as $responsible)
                                    <option value="{{ $responsible->id }}" @selected(old('responsible_id', $user->responsible_id) == $responsible->id)>

                                        {{ $responsible->nama_penanggung_jawab }}

                                    </option>
                                @endforeach

                            </select>

                            @error('responsible_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <hr>


                        {{-- PASSWORD BARU --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Password Baru
                            </label>

                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">

                            <div class="form-hint">
                                Kosongkan jika password tidak ingin diubah.
                            </div>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- KONFIRMASI PASSWORD --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Konfirmasi Password Baru
                            </label>

                            <input type="password" name="password_confirmation" class="form-control">

                        </div>


                        {{-- BUTTON --}}
                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Simpan Perubahan

                            </button>


                            <a href="{{ route('users.index') }}" class="btn btn-secondary">

                                Batal

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const role = document.getElementById('role');

            const responsibleWrapper =
                document.getElementById('responsible-wrapper');

            const responsibleSelect =
                document.getElementById('responsible_id');


            function updateResponsibleField() {

                if (role.value === 'responsible') {

                    responsibleWrapper.style.display = 'block';

                    responsibleSelect.required = true;

                } else {

                    responsibleWrapper.style.display = 'none';

                    responsibleSelect.required = false;

                    responsibleSelect.value = '';

                }

            }


            role.addEventListener('change', updateResponsibleField);


            updateResponsibleField();

        });
    </script>
@endpush
