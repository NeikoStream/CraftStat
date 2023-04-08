<?php

function TickToTime($tick){
    $tosecondes = floor($tick / 20);
    $heures = floor($tosecondes / 3600);        
    $minutes = floor(($tosecondes / 60) % 60);
    $secondes= $tosecondes % 60;

    return sprintf("%02d heures %02d minutes %02d secondes", $heures, $minutes, $secondes);
    }

?>