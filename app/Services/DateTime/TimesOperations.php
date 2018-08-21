<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/16/2018
 * Time: 3:18 PM
 */

namespace App\Services\DateTime;


trait TimesOperations
{

    public function compareTime($afterTime, $beforeTime){
        $aft = new \DateTime('2015-01-01 '.$afterTime);
        $bef = new \DateTime('2015-01-01 '.$beforeTime);

        if($aft > $bef){
            return true;
        } else {
            return false;
        }
    }
}