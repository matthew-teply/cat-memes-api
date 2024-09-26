<?php

namespace App\V1;

use App\V1\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

class Routes
{
    public static function register(): void {
        Route::prefix('v1')->group(function (): void {
            Route::get('/moods', [ImageController::class, 'getAvailableMoods'])->name('moods:list');
            Route::get('/image/random', [ImageController::class, 'getRandomImage'])->name('image:random');
            Route::post('/image/like/{imageId}', [ImageController::class, 'likeImage'])->name('image:like');
            Route::get('/image/{mood}/random', [ImageController::class, 'getRandomImageWithMood'])->name('image:random');
            Route::get('/image/{mood}/{name}', [ImageController::class, 'getImage'])->name('image:get');
        });
    }
}
