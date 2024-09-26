<?php

namespace App\V1\Repositories\Likes;

use App\V1\Models\Like;

class DatabaseLikeRepository implements LikeRepository
{
    public function setLike(int $imageId, string $ip): void {
        $like = new Like();

        $like->fill([
            'image_id' => $imageId,
            'ip' => $ip,
        ]);

        $like->save();
    }

    public function getIpLikeCount(int $imageId, string $ip): int
    {
        return Like::query()
            ->where('image_id', '=', $imageId)
            ->where('ip', '=', $ip)
            ->count();
    }

    public function getLikeCount(int $imageId): int {
        return Like::query()
            ->where('image_id', $imageId)
            ->count();
    }
}
