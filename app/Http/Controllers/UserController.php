<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::orderBy('level')->orderBy('name')->get();
        return view('server.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.pengaturan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'level' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level
        ]);

        return redirect()->back()->with('success', 'Success Add User!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cek apakah user memiliki tiket di tabel pemesanan
        $cekTiket = DB::table('pemesanan')->where('penumpang_id', $id)->exists();

        if ($cekTiket) {
            return redirect()->back()->with('error', 'Maaf, user tersebut memiliki tiket.');
        }

        // Jika tidak memiliki tiket, hapus user
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'User berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }


    public function name(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        User::find(Auth::user()->id)->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Nama anda berhasil diperbarui!');
    }

    public function password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::find(Auth::user()->id);

        if ($request->password_lama == true) {
            if (Hash::check($request->password_lama, $user->password)) {
                if ($request->password_lama == $request->password) {
                    return redirect()->back()->with('error', 'Maaf password yang anda masukkan sama!');
                } else {
                    $user_password = [
                        'password' => Hash::make($request->password),
                    ];
                    $user->update($user_password);
                    return redirect()->back()->with('success', 'Password anda berhasil diperbarui!');
                }
            } else {
                return redirect()->back()->with('error', 'Tolong masukkan password lama anda dengan benar!');
            }
        } else {
            return redirect()->back()->with('error', 'Tolong masukkan password lama anda terlebih dahulu!');
        }
    }
}
