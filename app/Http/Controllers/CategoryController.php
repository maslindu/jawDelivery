<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->get();
        return view('admin.categories', compact('categories'));
    }

    public function create()
    {
        return view('admin.add-category');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $category = Category::with('menus')->findOrFail($id);
        return view('admin.category-detail', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit-category', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has menus
        if ($category->menus()->count() > 0) {
            return redirect()->route('admin.manage-menu')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki menu!');
        }

        $category->delete();

        return redirect()->route('admin.manage-menu')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}