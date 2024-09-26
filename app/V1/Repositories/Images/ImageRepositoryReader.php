<?php

namespace App\V1\Repositories\Images;

use App\V1\Entity\Image;
use App\V1\Exceptions\RepositoryException;

interface ImageRepositoryReader
{
    /**
     * @throws RepositoryException
     */
    public function getImage(string $name, string $mood): Image;
}
