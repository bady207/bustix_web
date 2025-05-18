<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rute = Rute::count();
        $kategori = Category::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $transportasi = Transportasi::count();
        $user = User::where('level', 'Penumpang')->count();
        return view('server.home', compact('rute', 'pendapatan', 'transportasi', 'user','kategori'));
    }
}
