<?php

namespace App\V1\Factories;

use App\V1\Entity\Image;
use App\V1\Models\Image as ImageModel;
use App\V1\Services\Filesystems\FilesystemDriver;

readonly class ImageFactory
{
    public function __construct(
        private FilesystemDriver $filesystem,
    ) {}

    public function fromModel(ImageModel $model): Image {
        $mood = $model->getAttribute('image_category');
        $name = $model->getAttribute('image_name');

        return new Image(
            $model->getKey(),
            $name,
            $mood,
            $model->getAttribute('hits'),
            $this->filesystem->getImagePath($mood, $name)
        );
    }
}
