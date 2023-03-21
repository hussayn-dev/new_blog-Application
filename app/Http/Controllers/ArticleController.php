<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Category;
use App\Models\Tags;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $search = $request->input('search');

       if(empty($search)) {
        $all_articles = Article::paginate(15);
       } else {

        $articles = Article::like('title', $search)
        ->like('slug', $search)
        ->get();
        $articlesByTags = Tags::like('name', $search)
                    ->with('articles')
                ->get();
        $articlesByCategory = Category::like('name', $search)
                        ->with('articles')
                        ->get();
      $all_articles = array_merge($articles, $articlesByTags->articles, $articlesByCategory->articles);
       }

        return view('articles.index', compact('all_articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('articles.create');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string'
        ]);
        $cleaned = Purify::clean($validated['content']);

        $article = Article::create([
        'content' => $cleaned,
        'title' => $validated['title']
        ]);

        $images = $request->file('images');
        $positions = $request->input('positions');
        foreach ($images as $key => $image) {
            $filename = $image->store('public/images');
            $articleImage = ArticleImage::create([
                'filename' => $filename,
                'position' => isset($positions[$key]) ? $positions[$key] : $key,
                'article_id' => $article->id,
            ]);

            $articleImage->save();
        }

        $tags = $request->input('tags');
        foreach ($tags as $tag) {
        if(Tags::where('name', $tag)->exist()) {
           $tag_id = Tags::where('name', $tag)->first()->id;
           $article->tags()->attach($tag_id);
        }
        }

        $article->save();
        return redirect()->back()->with('success', 'Article saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        //
     $article = Article::where('slug', $slug)->with('tags')->firstOrFail();
     return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
         return view('articles.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string'
        ]);
        $cleaned = Purify::clean($validated['content']);

        $article = Article::where('slug', $slug)->first();

        if(!$article) {
            redirect()->back()->withErrors("error", "Category not found");
        }

        $article->title = $validated['title'];
        $article->content = $cleaned;

        $images = $request->file('images');
        $positions = $request->input('positions');
        foreach ($images as $key => $image) {
            $filename = $image->store('public/images');
            $articleImage = ArticleImage::create([
                'filename' => $filename,
                'position' => isset($positions[$key]) ? $positions[$key] : $key,
                'article_id' => $article->id,
            ]);

            $articleImage->update();
        }

        $tags = $request->input('tags');
        foreach ($tags as $tag) {
        if(Tags::where('name', $tag)->exist()) {
           $tag_id = Tags::where('name', $tag)->first()->id;
           $article->tags()->attach($tag_id);
        }
        }

        $article->update();
        return redirect()->back()->with('success', 'Article updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {

      $article = Article::where('slug', $slug)->first();

      if(!$article) {
        redirect()->back()->withErrors("error", "Article not found");
      }

      $article->delete();
      redirect()->back()->with("success", "Article deleted successfully");

    }
}
