<?php

namespace App\V1\Services\Filesystems;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class LocalFilesystemDriver implements FilesystemDriver
{
    protected Filesystem $disk;

    public function __construct(string $directory = 'public') {
        $this->disk = Storage::disk($directory);
    }

    public function getFile(string $path): string|null {
        return $this->disk->get($path);
    }

    public function getFolder(string $path): array|null {
        return $this->disk->allFiles($path);
    }

    public function listFolders(string $path): array|null {
        return $this->disk->allDirectories($path);
    }

    public function exists(string $path): bool {
        return $this->disk->exists($path);
    }

    public function getStorageUrl(string $path): string {
        return asset('storage/' . $path);
    }

    public function getMoodPath(string $mood): string {
       return $this->getStorageUrl('moods/' . $mood);
    }

    public function getImagePath(string $mood, string $imageFileName): string {
        return $this->getMoodPath($mood) . '/' . $imageFileName;
    }
}
