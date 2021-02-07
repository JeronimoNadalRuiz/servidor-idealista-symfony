<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

final class Ad implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private String $typology,
        private String $description,
        private array $pictures,
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

    public function getPictures(){
        return $this->pictures;
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
    public function setScore($score){
        if($score<0){
            $this->score = 0;
        }else{
            if($score>100){
                $this->score = 100;
            }else{
                $this->score = $score;
            }
        }
        if($this->score<40){
            $this->setIrrelevantSince(new DateTimeImmutable(date("Y-m-d H:i:s")));
        }
    }

    public function getIrrelevantSince(){
        return $this->irrelevantSince;
    }
    public function setIrrelevantSince(DateTimeImmutable $date){
        $this->irrelevantSince = $date;
    }

    public function jsonSerialize()
    {
        return [
            'id'=>$this->getId(),
            'typology'=>$this->getTypology(),
            'description'=>$this->getDescription(),
            'pictures'=>$this->getPictures(),
            'houseSize'=>$this->getHouseSize(),
            'gardenSize'=>$this->getGardenSize(),
            'score'=>$this->getScore(),
            'irrelevantSince'=>$this->getIrrelevantSince()
        ];
    }
}
