<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage(Request $request, $uuid)
    {
     $search = $request->input('search');
     $category = Category::findOrFail($uuid);
     $tags = Tags::where('category_id', $category->id)
                       ->like("name", $search)
                       ->paginate(15);
     return view('tags.manage', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , $uuid)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);

        $category = Category::find($uuid);

        if(!$category) {
            redirect()->back()->withErrors("error", "Category not found");
        }

        $tag = Tags::create([
            'name' => $validated['name'],
            'category_id' => $category->id,
        ]);

        $tag->save();

        redirect()->route('tags.create')->with('success', 'Tag has been created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
       $tag = Tags::findorFail($uuid);
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        $tag = Tags::find($uuid);

        if(!$tag) {
            redirect()->back()->withErrors("error", "The Tag was not found");
        }

        $tag->update($request->all());
        redirect()->back()->with("success", "Tag updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $tag = Tags::find($uuid);

        if(!$tag) {
            redirect()->back()->withErrors("error", "The Tag was not found");
        }

        $tag->delete();
        redirect()->back()->with("success", "Tag deleted successfully");
    }
}
