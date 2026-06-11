<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\GitHubController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/github', [GitHubController::class, 'redirect'])->name('auth.github');
Route::get('/auth/github/callback', [GitHubController::class, 'callback']);
Route::get('/oauth/callback', function () {
    return view('auth.oauth-callback');
})->name('oauth.callback');

Route::get('/dashboard', function () {
    return redirect('/posts');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('posts', PostController::class);
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

require __DIR__.'/auth.php';

