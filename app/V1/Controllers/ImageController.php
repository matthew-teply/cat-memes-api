<?php declare(strict_types=1);

namespace App\V1\Controllers;

use App\V1\Entity\Image;
use App\V1\Exceptions\ServiceException;
use App\V1\Services\ApiService;
use App\V1\Services\ImageService;
use App\V1\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

define('QUERY_PREVIOUS_IMAGE', 'previousImage');

/**
 * @OA\Info(
 *   title="Cat memes API",
 *   version="0.0.1"
 * )
 */
class ImageController
{
    public function __construct(
        public LikeService $likeService,
        public ImageService $imageService,
        public ApiService $apiService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/image/random",
     *     @OA\Response(response=200, description="Gets a random image of random mood")
     * )
     */
    public function getRandomImage(Request $request): JsonResponse {
        try {
            $randomMoodImage = $this->imageService->getRandomImageWithRandomMood(
                $request->get(QUERY_PREVIOUS_IMAGE)
            );
        } catch (ServiceException $exception) {
            return $this->apiService->response($exception->getMessage(), $exception->getCode());
        }

        return $this->respondWithImage($randomMoodImage);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/image/{mood}/{name}",
     *     @OA\Parameter(name="mood", in="path", required=true),
     *     @OA\Parameter(name="name", in="path", required=true),
     *     @OA\Response(response=200, description="Gets a specific image by its mood and name")
     * )
     */
    public function getImage(
        string $mood,
        string $name
    ): JsonResponse|string {
        if (empty($mood)) {
            return $this->apiService->response('Please specify image mood', Response::HTTP_BAD_REQUEST);
        }

        if (empty($name)) {
            return $this->apiService->response('Please specify image name', Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->apiService->response(
                $this->imageService->getImage($mood, $name)
            );
        } catch (ServiceException $exception) {
            return $this->apiService->response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/image/like/{imageId}",
     *     @OA\Parameter(name="imageId", in="path", required=true),
     *     @OA\Response(response=200, description="Likes an image, if the client's IP hasn't already liked the image")
     * )
     */
    public function likeImage(Request $request, int $imageId): JsonResponse {
        if ($request->ip() === null) {
            return $this->apiService->response('Could not determine IP', Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->likeService->likeImage($imageId, $request->ip());

            return $this->apiService->response('Image liked successfully', Response::HTTP_OK);
        } catch (ServiceException $exception) {
            return $this->apiService->response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/moods",
     *     @OA\Response(response=200, description="Returns an array of available mood folders")
     * )
     */
    public function getAvailableMoods(): JsonResponse {
        $availableMoods = $this->imageService->getAvailableMoods();

        if ($availableMoods === null) {
            return $this->apiService->response('No moods found', Response::HTTP_NOT_FOUND);
        }

        return $this->apiService->response($availableMoods, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/image/{mood}/random",
     *     @OA\Parameter(name="mood", in="path", required=true),
     *     @OA\Response(response=200, description="Gets a random image of a specific mood")
     * )
     */
    public function getRandomImageWithMood(Request $request, string $mood): JsonResponse {
        try {
            $randomImage = $this->imageService->getRandomImage($mood, $request->get(QUERY_PREVIOUS_IMAGE));
        } catch (ServiceException $exception) {
            return $this->apiService->response($exception->getMessage(), $exception->getCode());
        }

        return $this->respondWithImage($randomImage);
    }

    private function respondWithImage(Image $image): JsonResponse {
        $likesCount = $this->likeService->getImageLikesCount($image->id);

        return $this->apiService->imageResponse(
            $image,
            $likesCount,
            $likesCount > 0,
        );
    }
}
