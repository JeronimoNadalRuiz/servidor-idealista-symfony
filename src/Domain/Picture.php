<?php

declare(strict_types=1);

namespace App\Domain;

final class Picture
{
    public function __construct(
        private int $id,
        private String $url,
        private String $quality,
    ) {
    }

    public function getId(){
        return $this->id;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getQuality(){
        return $this->quality;
    }

}
