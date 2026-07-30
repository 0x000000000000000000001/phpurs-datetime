<?php

$createDate = function($y, $m, $d) {
    $dt = new \DateTime('now', new \DateTimeZone('UTC'));
    $dt->setDate($y, $m + 1, $d);
    $dt->setTime(0, 0, 0, 0);
    return $dt;
};

$canonicalDateImpl = function($ctor, $y, $m, $d) use (&$canonicalDateImpl, $createDate) {

    $date = $createDate($y, $m - 1, $d);
    return $ctor
        ((int)$date->format('Y'))
        ((int)$date->format('n'))
        ((int)$date->format('j'));
};

$calcWeekday = function($y, $m, $d) use (&$calcWeekday, $createDate) {

    $date = $createDate($y, $m - 1, $d);
    return (int)$date->format('w'); // 0 (for Sunday) through 6 (for Saturday)
};

$calcDiff = function($y1, $m1, $d1, $y2, $m2, $d2) use (&$calcDiff, $createDate) {

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
