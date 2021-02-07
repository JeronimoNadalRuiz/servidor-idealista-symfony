<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Persistence\InFileSystemPersistence;
use App\Domain\Ad;

final class CalculateScoreController
{

    public function __invoke(): JsonResponse
    {
        $persistence = new InFileSystemPersistence();
        $ads =  $persistence->getAds();

        foreach ($ads as $ad) {
            $score = 0;
            $score += $this->picutresScore($persistence, $ad);
            $score += $this->haveDescription($ad);
            $score += $this->sizeDescription($ad);
            $score += $this->keywordsDescription($ad);
            $score += $this->completeAd($persistence,$ad);
            
            $ad->setScore($score);
        }
        session_start();
        $_SESSION["ads"]=$ads;
        return new JsonResponse([$ads]);
    }

    public function picutresScore($persistence, $ad){

        $picturesAd=[];
        $score = 0;

        array_push($picturesAd,$persistence->getPicturesAd($ad));

        if(!$picturesAd[0]){
            $score = -10;
        }else{
            foreach ($picturesAd[0] as $picture) {
                if($picture->getQuality()=='HD'){
                    $score += 20;
                }
                if($picture->getQuality()=='SD'){
                    $score += 10;
                }
            }
        }
        return $score;
    }

    public function haveDescription($ad){

        $score = 0;

        if($ad->getDescription()){
            $score +=5;
        }

        return $score;
    }

    public function sizeDescription($ad){

        $score = 0;
        if($ad->getTypology()=='FLAT'){
            if(str_word_count($ad->getDescription()) >=20 && str_word_count($ad->getDescription())<=49){
                $score +=10;
            }
            if(str_word_count($ad->getDescription()) >50){
                $score +=30;
            }
           
        }

        if($ad->getTypology()=='CHALET'){
            if(str_word_count($ad->getDescription()) >50){
                $score +=20;
            }
        }

        return $score;
    }

    public function keywordsDescription($ad){

        $score = 0;

        if (stripos($ad->getDescription(), 'Luminoso') !== false) {
            $score +=5;
        }
        if (stripos($ad->getDescription(), 'Nuevo') !== false) {
            $score +=5;
        }
        if (stripos($ad->getDescription(), 'Céntrico') !== false) {
            $score +=5;
        }
        if (stripos($ad->getDescription(), 'Reformado') !== false) {
            $score +=5;
        }
        if (stripos($ad->getDescription(), 'Ático') !== false) {
            $score +=5;
        }
        return $score;
    }

    public function completeAd($persistence, $ad){
        $picturesAd=[];
        $score = 0;

        array_push($picturesAd,$persistence->getPicturesAd($ad));
        
        if($ad->getDescription() && $picturesAd[0]){
            if($ad->getTypology()=='FLAT' && $ad->getHouseSize()){
                $score +=40;
            }
            if($ad->getTypology()=='CHALET' && $ad->getHouseSize() && $ad->getGardenSize()){
                $score +=40;
            }
        }
        if($picturesAd[0] && $ad->getTypology()=='GARAGE'){
            $score +=40;
        }
        return $score;

    }

}
