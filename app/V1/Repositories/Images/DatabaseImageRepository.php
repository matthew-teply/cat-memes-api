<?php

namespace App\V1\Repositories\Images;

use App\V1\Entity\Image as ImageEntity;
use App\V1\Exceptions\RepositoryException;
use App\V1\Factories\ImageFactory;
use App\V1\Models\Image;

readonly class DatabaseImageRepository implements ImageRepository
{
    public function __construct(
        private ImageFactory $imageFactory,
    ) {}

    /**
     * @throws RepositoryException
     */
    public function getImage(
        string $name,
        string $mood,
    ): ImageEntity {
        $imageModel = Image::query()
            ->where('image_name', '=', $name)
            ->where('image_category', '=', $mood)
            ->first();

        if ($imageModel === null) {
            throw new RepositoryException('Image not found');
        } else {
            $hits = $imageModel->getAttribute('hits');

            $imageModel->update(['hits' => $hits + 1]);
        }

        return $this->imageFactory->fromModel($imageModel);
    }

    public function setImage(string $name, string $category): ImageEntity {
        $imageModel = new Image();

        $imageModel->fill([
            'image_name' => $name,
            'image_category' => $category,
        ]);

        $imageModel->save();

        return $this->imageFactory->fromModel($imageModel);
    }
}
