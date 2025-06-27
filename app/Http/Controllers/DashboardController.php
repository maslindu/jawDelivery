<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        $favMenuIds = $user?->menus?->pluck('id')->toArray() ?? [];
        $menuItems = Menu::with('categories')->get();
        $menuItems->each(function ($menu) use ($favMenuIds) {
            $menu->is_fav = in_array($menu->id, $favMenuIds);
        });
        return view('dashboard', compact('menuItems', 'categories'));
    }

    public function admin()
    {
        return view('admin.dashboard');
    }
    public function driver()
    {
        return view('drivers.dashboard');
    }

}
