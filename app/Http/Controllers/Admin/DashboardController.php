<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        // Dashboard admin yang sederhana, hanya menampilkan menu navigasi
        return view('admin.dashboard');
    }

    public function index()
    {
        // Method untuk dashboard pelanggan
        return view('dashboard');
    }
}
