<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

        public function index()
    {
        $users = User::all(); // Mengambil semua user dari database
        return view('admin.users', compact('users')); // Kirim data ke Blade
    }
}
