<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Ambil semua data user
        return response()->json([
            'success' => true,
            'message' => 'List of users',
            'data' => $users
        ], 200);
    }
}
