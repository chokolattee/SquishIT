<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function create()
    {
        return view('category.create'); 
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|unique:categories,description',
        ]);
    
        $category = new Category();
        $category->description = $request->description;
        $category->save();
    
        return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|unique:categories,description',
        ]);
        
        $category = Category::findOrFail($id);
        $category->description = $request->description;
        $category->save();
        
        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        
        return redirect()->route('admin.categories')->with('success', 'Category restored successfully.');
    }

}
