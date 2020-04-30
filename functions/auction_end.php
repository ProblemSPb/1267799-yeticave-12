<?php

// расчет времени до конца аукциона
function auction_end($auction_end_date) {
    $time_diff = strtotime($auction_end_date) - time();
    $hours = floor($time_diff / 3600);
    $mins = floor(($time_diff % 3600) / 60);
    $time_auc_end = [$hours, $mins];
    return $time_auc_end;

};