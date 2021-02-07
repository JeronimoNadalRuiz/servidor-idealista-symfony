<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Persistence\InFileSystemPersistence;
use App\Infrastructure\Api\QualityAd;

final class QualityListingController
{
    private array $qualityAds = [];

    public function __invoke(): JsonResponse
    {
        session_start();
        $ads = $_SESSION["ads"];
        foreach ($ads as $ad) {
            if($ad->getScore()<40){
                array_push($this->qualityAds, 
                new QualityAd(
                    $ad->getId(), 
                    $ad->getTypology(), 
                    $ad->getDescription(), 
                    array($this->getPicturesAd($ad)), 
                    $ad->getHouseSize(),
                    $ad->getGardenSize(),
                    $ad->getScore(),
                    $ad->getIrrelevantSince()
                ));
            }
        }
        //print_r($qualityAds);
        return new JsonResponse([$this->getQualityAds()]);
    }

    public function getPicturesAd($ad){
        $persistence = new InFileSystemPersistence();

        $picturesAd=[];
        $aux =[];
        $score = 0;

        array_push($picturesAd,$persistence->getPicturesAd($ad));
        foreach ($picturesAd[0] as $pictureAd) {
            array_push($aux,$pictureAd->getUrl());
        }
        return($aux);
    }

    public function getQualityAds(){
        return $this->qualityAds;
    }
}
