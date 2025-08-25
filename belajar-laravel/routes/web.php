<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ["title" => "Home Page"]);
});

Route::get('/posts', function () {
    $posts = Post::latest()->filter(request(['search', 'category', 'author']))->paginate(5)->withQueryString();

    return view('posts', ["title" => "Post Page", "posts" => $posts]);
});

Route::get('/posts/{post:slug}', function (Post $post) {
    return view('post', ["title" => "Single Post", "post" => $post]);
});

Route::get('/about', function () {
    return view('about', ["title" => "About Page"]);
});

Route::get('/contact', function () {
    return view('contact', ["title" => "Contact Page"]);
});

