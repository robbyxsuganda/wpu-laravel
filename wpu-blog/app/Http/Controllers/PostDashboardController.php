<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->where('author_id', Auth::user()->id);

        if(request('keywoard')){
            $posts->where('title', 'like', '%' . request('keywoard') . '%');
        }

        return view('dashboard.index', ['posts' => $posts->paginate(10)->withQueryString()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:10|max:255',
            'category_id' => 'required',
            'body' => 'required',
        ], [
            'category_id.required' => 'Pilih salah satu category',
            'required' => 'Field :attribute harus di isi'
        ])->validate();

        Post::create([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body,
        ]);

        return redirect('/dashboard')->with(['success' => 'Add Post Success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('dashboard.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
         $request->validate([
            'title' => 'required|min:10|max:255|unique:posts,title' . $post->id,
            'category_id' => 'required',
            'body' => 'required',
        ]);

        $post->update([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body,
        ]);

        return redirect('/dashboard')->with(['success' => 'Update Post Success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/dashboard')->with(['success' => 'Delete post success']);
    }
}
