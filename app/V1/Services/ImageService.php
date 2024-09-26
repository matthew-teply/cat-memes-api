<?php

namespace App\V1\Services;

use App\V1\Entity\Image;
use App\V1\Exceptions\RepositoryException;
use App\V1\Exceptions\ServiceException;
use App\V1\Repositories\Images\ImageRepository;
use App\V1\Services\Filesystems\FilesystemDriver;
use Symfony\Component\HttpFoundation\Response;

class ImageService
{
    public function __construct(
        public FilesystemDriver $filesystem,
        public ImageRepository $imageRepository,
    ) {}

    public function getAvailableMoods(): array|null {
        return array_map(function ($moodPath) {
            return explode('/', $moodPath)[1];
        }, $this->filesystem->listFolders('moods/'));
    }

    /**
     * @throws ServiceException
     */
    public function getImage(string $mood, string $name): Image {
        try {
            return $this->imageRepository->getImage($name, $mood);
        } catch (RepositoryException $exception) {
            throw new ServiceException($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @throws ServiceException
     */
    public function getRandomImageWithRandomMood(
        string|null $previousImage = null,
    ): Image {
        $availableMoodFolders = $this->getAvailableMoods();
        $randomMoodFolder = $availableMoodFolders[rand(0, count($availableMoodFolders) - 1)];

        return $this->getRandomImage(
            $randomMoodFolder,
            $previousImage,
        );
    }

    /**
     * @throws ServiceException
     */
    public function getRandomImage(
        string $mood,
        string|null $previousImage = null,
    ): Image {
        $moodPath = 'moods/' . $mood;

        if (!$this->filesystem->exists($moodPath)) {
            throw new ServiceException('Mood "' . $mood . '" does not exist', Response::HTTP_NOT_FOUND);
        }

        $moodImages = $this->filesystem->getFolder('moods/' . $mood);

        if ($previousImage !== null) {
            $moodImages = array_values(array_filter($moodImages, fn ($image): bool => $image !== 'moods/' . $previousImage));
        }

        if (empty($moodImages)) {
            throw new ServiceException('Mood "' . $mood . '" has no images', Response::HTTP_NOT_FOUND);
        }

        $explodedRandomImage = explode(
            '/',
            $moodImages[rand(0, count($moodImages) - 1)]
        );

        $randomMoodImageName = $explodedRandomImage[count($explodedRandomImage) - 1];

        return $this->imageRepository->getImage(
            $randomMoodImageName,
            $mood,
        );
    }
}
