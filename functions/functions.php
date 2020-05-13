<?php

// расчет времени до конца аукциона
function auction_end($auction_end_date) {
    $time_diff = strtotime($auction_end_date) - time();
    $hours = floor($time_diff / 3600);
    $mins = floor(($time_diff % 3600) / 60);
    $time_auc_end = [$hours, $mins];
    return $time_auc_end;

};

// функция подключения темплейтов
function include_template($file_name, $data = array()) {
    $path ='templates/'.$file_name;
    $result = '';

    // если файл не найден
    if(!file_exists($path)) {
        return $result;
    };

    ob_start();
    extract($data);
    require_once $path;
    $result = ob_get_clean();
    return $result;

};

// форматирование суммы цены
function price_format($num) {
    $num = ceil($num); // округляем
    if ($num >= 1000) {
        $num = number_format($num, 0, ',', ' '); // форматируем число
    }

    $num = $num." ₽"; // добавляем рубли
    return $num;
};
