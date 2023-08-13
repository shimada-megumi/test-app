<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NiceController;

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

Route::get('/', function () {
    return view('welcome');
})->name('top');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// お問い合わせ
Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create');
Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');

 // ログイン後の通常のユーザー画面
Route::middleware(['verified'])->group(function(){
    Route::get('post/mycomment', [Postcontroller::class, 'mycomment'])->name('post.mycomment');
    Route::get('post/mypost', [PostController::class, 'mypost'])->name('post.mypost');
    Route::resource('post', PostController::class);
    Route::post('post/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 管理者用画面
    Route::middleware(['can:admin'])->group(function () {
        Route::get('profile/index', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/adedit/{user}',
        [ProfileController::class, 'adedit'])->name('profile.adedit');
        Route::patch('/profile/adupdate/{user}', [ProfileController::class, 'adupdate'])
        ->name('profile.adupdate');

        Route::delete('profile/{user}', [ProfileController::class, 'addestroy'])->name('profile.addestroy');
        Route::patch('roles/{user}/attach', [RoleController::class, 'attach'])->name('role.attach');
        Route::patch('roles/{user}/detach', [RoleController::class, 'detach'])->name('role.detach');
    });

    // いいねボタン
    Route::get('/community/nice/{post}', [NiceController::class, 'nice'])->name('nice');
    Route::get('/community/unnice/{post}', [NiceController::class, 'unnice'])->name('unnice');
});

require __DIR__.'/auth.php';
