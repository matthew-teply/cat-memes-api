<?php

namespace App\V1\Repositories\Images;

use App\V1\Entity\Image;

interface ImageRepositoryWriter
{
    public function setImage(string $name, string $category): Image;
}
