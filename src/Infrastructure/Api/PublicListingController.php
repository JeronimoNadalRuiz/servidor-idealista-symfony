<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Persistence\InFileSystemPersistence;
use App\Infrastructure\Api\PublicAd;

final class PublicListingController
{
    private array $publicAds = [];

    public function __invoke(): JsonResponse
    {
        session_start();
        $ads = $_SESSION["ads"];
        foreach ($ads as $ad) {
            if($ad->getScore()>40){
                array_push($this->publicAds, 
                new PublicAd(
                    $ad->getId(), 
                    $ad->getTypology(), 
                    $ad->getDescription(), 
                    array($this->getPicturesAd($ad)), 
                    $ad->getHouseSize(),
                    $ad->getGardenSize(),
                    $ad->getScore()
                ));
            }
        }
        return new JsonResponse([$this->getPublicAds()]);
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

    public function getPublicAds(){
        $score = array(); 
        foreach ($this->publicAds as $ad) {
            $score[] = $ad->getScore(); //any object field
        }

        array_multisort($score, SORT_DESC, $this->publicAds);
        return $this->publicAds;
    }


}
