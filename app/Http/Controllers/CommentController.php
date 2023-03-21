<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $uuid)
    {
        //
        $validated = $request->validate([

        ]);
        $article = Article::where('uuid', $uuid)->first();

        if(!$article) {
         return redirect()->back()->withErrors('error', 'Something went wrong, try again later');
        }

        $user = auth()->user();

        $comment = Comment::create([
         'message' => $validated['message'],
         'user_id'  => $user->id,
        'article_id' => $article->id
        ]);
           $comment->save();
        return redirect()->back();
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
    public function destroy(string $id)
    {
        //
    }
}
