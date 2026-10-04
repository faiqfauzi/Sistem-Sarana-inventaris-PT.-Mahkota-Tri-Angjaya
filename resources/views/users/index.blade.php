@extends('layouts.app')

@section('title', 'Akun')

@section('content')

    <div class="page-header d-print-none mb-3">

        <div class="container-xl">

            <div class="row g-2 align-items-center">

                <div class="col">

                    <h2 class="page-title">
                        Akun
                    </h2>

                    <div class="text-secondary">
                        Kelola akun pengguna sistem
                    </div>

                </div>


                <div class="col-auto ms-auto">

                    <a href="{{ route('users.create') }}" class="btn btn-primary">

                        <i class="ti ti-plus me-1"></i>

                        Tambah

                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="container-xl">


        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger">

                {{ session('error') }}

            </div>
        @endif


        <div class="card">


            <div class="table-responsive">

                <table class="table table-vcenter table-hover">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Username</th>

                            <th>Role</th>

                            <th>Penanggung Jawab</th>

                            <th width="140">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($users as $user)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    {{ $user->username }}

                                </td>

                                <td>

                                    @if ($user->role === 'admin')
                                        <span>
                                            Admin
                                        </span>
                                    @else
                                        <span>
                                            Penanggung Jawab
                                        </span>
                                    @endif

                                </td>


                                <td>

                                    {{ $user->responsible?->nama_penanggung_jawab ?? '-' }}

                                </td>


                                <td>

                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm btn-icon"
                                        title="Edit">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>

                                    </a>


                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">

                                            @csrf

                                            @method('DELETE')


                                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus akun ini?')">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6v14H5V6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                </svg>

                                            </button>

                                        </form>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    Belum ada akun.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
