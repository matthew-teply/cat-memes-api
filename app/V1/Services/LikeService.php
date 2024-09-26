<?php

namespace App\V1\Services;

use App\V1\Exceptions\ServiceException;
use App\V1\Repositories\Likes\LikeRepository;
use Symfony\Component\HttpFoundation\Response;

readonly class LikeService
{
    public function __construct(
        private LikeRepository $likeRepository,
    ) {}

    public function isImageLiked(int $imageId, string $ip): bool {
        $likesCount = $this->likeRepository->getIpLikeCount($imageId, $ip);

        if ($likesCount > 0) {
            return true;
        }

        return false;
    }

    /**
     * @throws ServiceException
     */
    public function likeImage(int $imageId, string $ip): void {
        if (!$this->isImageLiked($imageId, $ip)) {
            $this->likeRepository->setLike($imageId, $ip);
            return;
        }

        throw new ServiceException('Image already liked', Response::HTTP_BAD_REQUEST);
    }

    public function getImageLikesCount(int $imageId): int {
        return $this->likeRepository->getLikeCount($imageId);
    }
}
