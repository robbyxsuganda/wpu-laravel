<?php

use App\Http\Controllers\PostDashboardController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', [PostDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard',[PostDashboardController::class, 'store']);
    Route::get('/dashboard/create',[PostDashboardController::class, 'create']);
    Route::get('/dashboard/{post:slug}',[PostDashboardController::class, 'show']);
    Route::delete('/dashboard/{post:slug}',[PostDashboardController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
