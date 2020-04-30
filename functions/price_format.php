<?php

// форматирование суммы цены
function price_format($num) {
    $num = ceil($num); // округляем
    if ($num >= 1000) {
        $num = number_format($num, 0, ',', ' '); // форматируем число
    }

    $num = $num." ₽"; // добавляем рубли
    return $num;
};