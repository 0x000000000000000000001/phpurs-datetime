<?php

$fromDateTimeImpl = function($y, $mo, $d, $h, $mi, $s, $ms) use (&$fromDateTimeImpl) {
    $dt = new \DateTime('now', new \DateTimeZone('UTC'));
    $dt->setDate($y, $mo, $d);
    $dt->setTime($h, $mi, $s, $ms * 1000);
    return (float)$dt->getTimestamp() * 1000 + (int)$dt->format('v');
};

$toDateTimeImpl = function($ctor, $instant) use (&$toDateTimeImpl) {
    $seconds = floor($instant / 1000);
    $ms = $instant - ($seconds * 1000);
    $dt = new \DateTime("@" . $seconds, new \DateTimeZone('UTC'));
    
    return $ctor
        ((int)$dt->format('Y'))
        ((int)$dt->format('n'))
        ((int)$dt->format('j'))
        ((int)$dt->format('G'))
        ((int)$dt->format('i'))
        ((int)$dt->format('s'))
        ((int)$ms);
};

$exports['fromDateTimeImpl'] = $fromDateTimeImpl;
$exports['toDateTimeImpl'] = $toDateTimeImpl;
return $exports;
