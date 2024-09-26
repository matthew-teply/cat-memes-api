<?php

namespace App\V1\Services\Filesystems;

interface FilesystemDriver {
    public function getFile(string $path): string | null;

    /**
     * @return string[]|null
     */
    public function getFolder(string $path): array | null;

    public function listFolders(string $path): array | null;

    public function exists(string $path): bool;

    public function getStorageUrl(string $path): string;

    public function getMoodPath(string $mood): string;

    public function getImagePath(string $mood, string $imageFileName): string;
}
