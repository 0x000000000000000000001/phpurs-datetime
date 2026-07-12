<?php

$createUTC = function($y, $mo, $d, $h, $m, $s, $ms) use (&$createUTC) {
    $dt = new \DateTime('now', new \DateTimeZone('UTC'));
    $dt->setDate($y, $mo + 1, $d);
    $dt->setTime($h, $m, $s, $ms * 1000);
    return (float)$dt->getTimestamp() * 1000 + (int)$dt->format('v');
};

$calcDiff = function($rec1, $rec2 = null) use (&$calcDiff) {
    if (\func_num_args() < 2) {
        $__args = \func_get_args();
        return function(...$more) use ($__args, &$calcDiff) {

            return $calcDiff(...\array_merge($__args, $more));
        };
    }

    $msUTC1 = $createUTC($rec1->year, $rec1->month - 1, $rec1->day, $rec1->hour, $rec1->minute, $rec1->second, $rec1->millisecond);
    $msUTC2 = $createUTC($rec2->year, $rec2->month - 1, $rec2->day, $rec2->hour, $rec2->minute, $rec2->second, $rec2->millisecond);
    return $msUTC1 - $msUTC2;
};

$adjustImpl = function($just, $nothing = null, $offset = null, $rec = null) use (&$adjustImpl) {
    if (\func_num_args() < 4) {
        $__args = \func_get_args();
        return function(...$more) use ($__args, &$adjustImpl) {

            return $adjustImpl(...\array_merge($__args, $more));
        };
    }

    $msUTC = $createUTC($rec->year, $rec->month - 1, $rec->day, $rec->hour, $rec->minute, $rec->second, $rec->millisecond);
    $targetMs = $msUTC + $offset;
    
    $seconds = floor($targetMs / 1000);
    $ms = $targetMs - ($seconds * 1000);
    
    try {
        $dt = new \DateTime("@" . $seconds, new \DateTimeZone('UTC'));
        return $just((object)[
            'year' => (int)$dt->format('Y'),
            'month' => (int)$dt->format('n'),
            'day' => (int)$dt->format('j'),
            'hour' => (int)$dt->format('G'),
            'minute' => (int)$dt->format('i'),
            'second' => (int)$dt->format('s'),
            'millisecond' => (int)$ms
        ]);
    } catch (\Exception $e) {
        return $nothing;
    }
};

$exports['createUTC'] = $createUTC;
$exports['calcDiff'] = $calcDiff;
$exports['adjustImpl'] = $adjustImpl;
return $exports;
