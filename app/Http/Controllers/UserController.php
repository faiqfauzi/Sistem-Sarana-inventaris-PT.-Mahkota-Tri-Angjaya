<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Responsible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('responsible')
            ->orderBy('username')
            ->get();

        return view(
            'users.index',
            compact('users')
        );
    }


    public function create()
    {
        $responsibles = Responsible::orderBy(
            'nama_penanggung_jawab'
        )->get();

        return view(
            'users.create',
            compact('responsibles')
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:admin,responsible',
            ],

            'responsible_id' => [
                'nullable',
                'exists:responsibles,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI ROLE RESPONSIBLE
        |--------------------------------------------------------------------------
        */

        if (
            $request->role === 'responsible' &&
            !$request->responsible_id
        ) {

            return back()
                ->withErrors([
                    'responsible_id' =>
                    'Penanggung jawab wajib dipilih.',
                ])
                ->withInput();
        }


        User::create([
            'username' => $request->username,

            'password' => Hash::make(
                $request->password
            ),

            'role' => $request->role,

            'responsible_id' =>
            $request->role === 'responsible'
                ? $request->responsible_id
                : null,
        ]);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dibuat.'
            );
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        $responsibles = Responsible::orderBy(
            'nama_penanggung_jawab'
        )->get();

        return view(
            'users.edit',
            compact(
                'user',
                'responsibles'
            )
        );
    }


    public function update(
        Request $request,
        User $user
    ) {

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
            ],

            'role' => [
                'required',
                'in:admin,responsible',
            ],

            'responsible_id' => [
                'nullable',
                'exists:responsibles,id',
            ],
        ]);


        if (
            $request->role === 'responsible' &&
            !$request->responsible_id
        ) {

            return back()
                ->withErrors([
                    'responsible_id' =>
                    'Penanggung jawab wajib dipilih.',
                ])
                ->withInput();
        }


        $data = [
            'username' => $request->username,

            'role' => $request->role,

            'responsible_id' =>
            $request->role === 'responsible'
                ? $request->responsible_id
                : null,
        ];


        /*
        |--------------------------------------------------------------------------
        | PASSWORD HANYA DIUBAH JIKA DIISI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            $request->validate([
                'password' => [
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ]);

            $data['password'] = Hash::make(
                $request->password
            );
        }


        $user->update($data);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil diperbarui.'
            );
    }


    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | JANGAN BIARKAN ADMIN MENGHAPUS DIRINYA SENDIRI
        |--------------------------------------------------------------------------
        */

        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }


        $user->delete();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dihapus.'
            );
    }
}
