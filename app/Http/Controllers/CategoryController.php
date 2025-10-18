<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10); // Pagination
        return view('modules.category.index', compact('categories'));
    }

    public function create()
    {
        return view('modules.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:50|unique:categories,code',
        ]);
        Category::create($validated);

        return redirect()->route('category.index')->with('success', 'Category created!');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('modules.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('modules.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:50|unique:categories,code,' . $category->id,
        ]);
        $category->update($validated);

        return redirect()->route('category.index')->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('category.index')->with('success', 'Category deleted!');
    }
}
