<?php

namespace App\V1\Repositories\Likes;

interface LikeRepositoryReader {
    public function getIpLikeCount(int $imageId, string $ip): int;

    public function getLikeCount(int $imageId): int;
}
