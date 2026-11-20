<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories=Category::all();
        return view('admin.categories.index',compact('categories'));
    }
    public function create(){
        return view('admin.categories.create');
    }   
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255|unique:categories,name',
            'description'=>'nullable|string',
        ]);
        Category::create([
            'name'=>$request->name,
            'description'=>$request->description,
        ]);
        return redirect('/admin/categories')->with('success','Category created Successfully');

    }
    public function edit(Category $category){
        return view('admin.categories.edit',compact('category'));

    }
    public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'description' => 'nullable|string',
    ]);

    $category->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    return redirect('/admin/categories')
        ->with('success', 'Category updated successfully.');
    }
    public function destroy(Category $category)
{
    // Prevent deleting a category that is assigned to products
    if ($category->products()->exists()) {
        return redirect('/admin/categories')
            ->with('error', 'Cannot delete this category because products are assigned to it.');
    }

    $category->delete();

    return redirect('/admin/categories')
        ->with('success', 'Category deleted successfully.');
    }
}
