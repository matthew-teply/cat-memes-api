<?php

namespace App\V1\Entity;

class Image
{
    public string $extension;
    public string $path;

    public function __construct(
        public int $id,
        public string $name,
        public string $mood,
        public int $hits,
        public string $url,
    ) {
        $this->extension = explode('.', $this->name)[1];
        $this->path = $this->mood . '/' . $this->name;
    }
}
