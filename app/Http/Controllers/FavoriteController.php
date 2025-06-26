<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $menus = $user->menus;
        return view('favorite-menu', compact('menus'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $user = Auth::user();
        $menuId = $request->menu_id;

        if (! $user->menus()->where('menu_id', $menuId)->exists()) {
            $user->menus()->attach($menuId);
        }

        return response()->json(['message' => 'success']);
    }


    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $user = Auth::user();
        $menuId = $request->menu_id;

        $user->menus()->detach($menuId);
        return response()->json(['message' => 'success']);
    }
}
