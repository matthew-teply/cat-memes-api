<?php

namespace App\V1\Services;

use App\V1\Entity\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ApiService
{
    /**
     * @param mixed[]|string|object $data
     */
    public function response(array|string|object $data, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = new Collection();

        if (is_string($data)) {
            $response->put('message', $data);
        } else {
            $response = $data;
        }

        // We want to append the error code into the response body itself
        // with every error
        if ($code >= 400 && $response instanceof Collection) {
            $response->put('code', $code);
        }

        return response()
            ->json($response, $code)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function imageResponse(
        Image $image,
        int $likesCount,
        bool $isLiked,
    ): JsonResponse {
        return ApiService::response([
            'image' => $image,
            'likes' => [
                'count' => $likesCount,
                'isLiked' => $isLiked,
            ]
        ]);
    }
}
