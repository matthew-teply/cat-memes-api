<?php

namespace App\V1;

use App\V1\Repositories\Images\DatabaseImageRepository;
use App\V1\Repositories\Images\ImageRepository;
use App\V1\Repositories\Likes\DatabaseLikeRepository;
use App\V1\Repositories\Likes\LikeRepository;
use App\V1\Services\Filesystems\FilesystemDriver;
use App\V1\Services\Filesystems\LocalFilesystemDriver;
use Illuminate\Support\ServiceProvider;

class V1ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            FilesystemDriver::class,
            env('APP_V1_FILESYSTEM_DRIVER', LocalFilesystemDriver::class)
        );

        $this->app->bind(
            ImageRepository::class,
            env('APP_V1_IMAGE_REPOSITORY_DRIVER', DatabaseImageRepository::class)
        );

        $this->app->bind(
            LikeRepository::class,
            env('APP_V1_LIKE_REPOSITORY_DRIVER', DatabaseLikeRepository::class)
        );
    }

    public function boot(): void
    {
    }
}
