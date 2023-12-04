
<?php

use App\Http\Controllers\HeadlineController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\WebGalleryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return Redirect::to('/');
});
Route::get('/', [WebController::class, 'index']);
Route::get('/id/{rubrik}/{post_id}/{slug}', [WebController::class, 'singlePost'])
    ->where('rubrik', '[\w\s-]+')
    ->name('singlePost');
Route::get('/category/{rubrik_name}', [WebController::class, 'category'])->name('category');
Route::get('/tags/{tag_name}', [WebController::class, 'tags'])->name('tags');
Route::get('/search', [WebController::class, 'search'])->name('search');
Route::get('/indeks-berita', [WebController::class, 'indeks'])->name('indeks');
Route::get('/search', [WebController::class, 'search'])->name('search');
Route::get('/topik-khusus/detail/{topic_id}/{slug}', [WebController::class, 'topikkhusus'])->name('topikkhusus');
Route::get('/subs', [WebController::class, 'subscribe'])->name('subscribe');
Route::get('/video', [WebGalleryController::class, 'video'])->name('video');
Route::get('/video/detail/{video_id}/{title}', [WebGalleryController::class, 'videtail'])->name('videtail');
Route::get('/image', [WebGalleryController::class, 'image'])->name('image');
