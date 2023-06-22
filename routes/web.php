<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController; //コントローラーを読み込ませる
use App\Http\Controllers\FavoriteController; //いいねコントローラーを読み込ませる
use App\Http\Controllers\FollowController; //フォローコントローラーを読み込ませる

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::resource('tweet', TweetController::class); //コントローラーに書いたコード

//ログインしていなければ動かないようにしたいものをこの｛｝の中に入れる
//ログインしなくても動かせるようにしたいものはこの式の外に書く
Route::middleware('auth')->group(function () {
    //自分で作る関数は上に書く
    //フォローしている人の投稿のみのタイムラインを作成する処置
    Route::get('/tweet/timeline', [TweetController::class, 'timeline'])->name('tweet.timeline');

    //フォロー一覧からユーザーページを作成する処置
    Route::get('user/{user}', [FollowController::class, 'show'])->name('follow.show');

    //フォローしたときの処置
    Route::post('user/{user}/follow', [FollowController::class, 'store'])->name('follow');
    Route::post('user/{user}/unfollow', [FollowController::class, 'destroy'])->name('unfollow');

    //いいねした時の処置
    Route::post('tweet/{tweet}/favorites', [FavoriteController::class, 'store'])->name('favorites');
    Route::post('tweet/{tweet}/unfavorites', [FavoriteController::class, 'destroy'])->name('unfavorites');

    //ユーザーとツイートを紐付ける
    Route::get('/tweet/mypage', [TweetController::class, 'mydata'])->name('tweet.mypage');
    Route::resource('tweet', TweetController::class);
    //ツイートのページを動作させる
    Route::resource('tweet', TweetController::class);

});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
