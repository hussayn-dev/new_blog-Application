<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
     $search = $request->input('search');
     $categories = Category::like("name", $search)->paginate(15);
     return view('category.manage', compact('categories'));
    }

    public function manage(Request $request)
    {
        $categories = Category::paginate(15);
        return view('category.manage', compact('categories'));

    }
    /**
     * Show the form for creating a new resource.
     */
    // view to create new category
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);

        $category = Category::create([
            'name' => $validated['name']
        ]);

        $category->save();

        redirect()->route('category.create')->with('success', 'Category has been created successfully');

    }

    /**
     * Display the specified resource.
     */
    //show a category
    public function show(string $uuid)
    {
    $category = Category::where(['uuid' => $uuid])->with(['articles.tags' => function ($query) {
        $query->select('name');
    }])->firstOrFail();

    return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $category = Category::where('uuid',$uuid)->firstOrFail();
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        $category = Category::where('uuid',$uuid)->first();

        if(!$category) {
            redirect()->back()->withErrors("error", "Category not found");
        }

        $category->update($request->all());
        redirect()->back()->with("success", "Category updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
      $category = Category::where('uuid',$uuid)->first();

    if(!$category) {
        redirect()->back()->withErrors("error", "Category not found");
    }

    $category->delete();
    redirect()->back()->with("success", "Category deleted successfully");

    }
}
