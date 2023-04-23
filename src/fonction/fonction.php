<?php

function TickToTime($tick){
    $tosecondes = intval(floor($tick / 20));
    $heures = intval(floor($tosecondes / 3600));        
    $minutes = intval(floor($tosecondes / 60) % 60);
    $secondes= $tosecondes % 60;
    if($heures == 0 && $minutes == 0){
        return sprintf("%d secondes", $secondes);
    } elseif($heures == 0){
        return sprintf("%d minutes %d secondes", $minutes, $secondes);
    } else{
        return sprintf("%d heures %d minutes %d secondes", $heures, $minutes, $secondes);
    }
    }



function TickToTimeShort($tick){
    $tosecondes = intval(floor($tick / 20));
    $heures = intval(floor($tosecondes / 3600));        
    $minutes = intval(floor($tosecondes / 60) % 60);
    $secondes= $tosecondes % 60;
    if($heures == 0 && $minutes == 0){
        return sprintf("%ds", $secondes);
    } elseif($heures == 0){
        return sprintf("%dm%ds", $minutes, $secondes);
    } else{
        return sprintf("%dh%dm%ds", $heures, $minutes, $secondes);
    }
    
    }

?>