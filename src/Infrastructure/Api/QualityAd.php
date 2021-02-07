<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use DateTimeImmutable;

final class QualityAd implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private String $typology,
        private String $description,
        private array $pictureUrls,
        private int $houseSize,
        private ?int $gardenSize = null,
        private ?int $score = null,
        private ?DateTimeImmutable $irrelevantSince = null,
    ) {
    }
    public function getId(){
        return $this->id;
    }

    public function getTypology(){
        return $this->typology;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getPictureUrls(){
        return $this->pictureUrls;
    }

    public function getHouseSize(){
        return $this->houseSize;
    }

    public function getGardenSize(){
        return $this->gardenSize;
    }

    public function getScore(){
        return $this->score;
    }
    public function getIrrelevantSince(){
        return $this->irrelevantSince;
    }

    public function jsonSerialize()
    {
        return [
            'id'=>$this->getId(),
            'typology'=>$this->getTypology(),
            'description'=>$this->getDescription(),
            'pictureUrls'=>$this->getPictureUrls(),
            'houseSize'=>$this->getHouseSize(),
            'gardenSize'=>$this->getGardenSize(),
            'score'=>$this->getScore(),
            'irrelevantSince'=>$this->getIrrelevantSince()

        ];
    }
}
