<?php

$createDate = function($y, $m, $d) use (&$createDate) {
    $dt = new \DateTime('now', new \DateTimeZone('UTC'));
    $dt->setDate($y, $m + 1, $d);
    $dt->setTime(0, 0, 0, 0);
    return $dt;
};

$canonicalDateImpl = function($ctor, $y = null, $m = null, $d = null) use (&$canonicalDateImpl) {
    if (func_num_args() < 4) {
        $__args = func_get_args();
        return function(...$more) use ($__args, &$createDate) {

            return $canonicalDateImpl(...array_merge($__args, $more));
        };
    }

    $date = $createDate($y, $m - 1, $d);
    return $ctor
        ((int)$date->format('Y'))
        ((int)$date->format('n'))
        ((int)$date->format('j'));
};

$calcWeekday = function($y, $m = null, $d = null) use (&$calcWeekday) {
    if (func_num_args() < 3) {
        $__args = func_get_args();
        return function(...$more) use ($__args, &$canonicalDateImpl) {

            return $calcWeekday(...array_merge($__args, $more));
        };
    }

    $date = $createDate($y, $m - 1, $d);
    return (int)$date->format('w'); // 0 (for Sunday) through 6 (for Saturday)
};

$calcDiff = function($y1, $m1 = null, $d1 = null, $y2 = null, $m2 = null, $d2 = null) use (&$calcDiff) {
    if (func_num_args() < 6) {
        $__args = func_get_args();
        return function(...$more) use ($__args, &$calcWeekday) {

            return $calcDiff(...array_merge($__args, $more));
        };
    }

    $dt1 = $createDate($y1, $m1 - 1, $d1);
    $dt2 = $createDate($y2, $m2 - 1, $d2);
    // returns diff in milliseconds
    return ($dt1->getTimestamp() - $dt2->getTimestamp()) * 1000;
};

$exports['createDate'] = $createDate;
$exports['canonicalDateImpl'] = $canonicalDateImpl;
$exports['calcWeekday'] = $calcWeekday;
$exports['calcDiff'] = $calcDiff;
return $exports;
