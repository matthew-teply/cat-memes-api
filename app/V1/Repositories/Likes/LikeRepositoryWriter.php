<?php

namespace App\V1\Repositories\Likes;

interface LikeRepositoryWriter {
    public function setLike(int $imageId, string $ip): void;
}
