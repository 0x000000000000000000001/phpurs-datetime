<?php

$fromDateTimeImpl = function($y, $mo = null, $d = null, $h = null, $mi = null, $s = null, $ms = null) use (&$fromDateTimeImpl) {
    if (func_num_args() < 7) {
        $__args = func_get_args();
        return function(...$more) use ($__args, &$fromDateTimeImpl) {

            return $fromDateTimeImpl(...array_merge($__args, $more));
        };
    }
    $dt = new \DateTime('now', new \DateTimeZone('UTC'));
    $dt->setDate($y, $mo, $d);
    $dt->setTime($h, $mi, $s, $ms * 1000);
    return (float)$dt->getTimestamp() * 1000 + (int)$dt->format('v');
};

$toDateTimeImpl = function($ctor, $instant = null) use (&$toDateTimeImpl) {
    if (func_num_args() < 2) {
        $__args = func_get_args();
        return function(...$more) use ($__args, &$toDateTimeImpl) {

            return $toDateTimeImpl(...array_merge($__args, $more));
        };
    }
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
