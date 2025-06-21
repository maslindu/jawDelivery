<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        $menuItems = Menu::all();
        $categories = Category::all();
        return view('dashboard', compact('menuItems', 'categories'));
    }
    public function admin()
    {
        return view('admin.dashboard');
    }
}
