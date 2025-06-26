<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = Menu::with('categories')->get();
        return view('menu', compact('menuItems'));
    }

    public function adminIndex()
    {
        $menuItems = Menu::with('categories')->get();
        $categories = Category::all();
        return view('admin.manage-menu', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.add-menu', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        $menu = Menu::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image_link' => $imagePath,
        ]);

        if ($request->has('categories')) {
            $menu->categories()->attach($request->categories);
        }

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $menu = Menu::with('categories')->findOrFail($id);
        return view('admin.menu-detail', compact('menu'));
    }

    public function edit(string $id)
    {
        $menu = Menu::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('admin.edit-menu', compact('menu', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = $menu->image_link;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image_link && Storage::disk('public')->exists($menu->image_link)) {
                Storage::disk('public')->delete($menu->image_link);
            }
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        $menu->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image_link' => $imagePath,
        ]);

        if ($request->has('categories')) {
            $menu->categories()->sync($request->categories);
        } else {
            $menu->categories()->detach();
        }

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        // Delete image if exists
        if ($menu->image_link && Storage::disk('public')->exists($menu->image_link)) {
            Storage::disk('public')->delete($menu->image_link);
        }

        // Detach categories
        $menu->categories()->detach();

        // Delete menu
        $menu->delete();

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Menu berhasil dihapus!');
    }
}