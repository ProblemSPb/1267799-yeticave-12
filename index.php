<?php

$is_auth = rand(0, 1);
$user_name = 'Lena'; // укажите здесь ваше имя
$title = 'YetiCave';

// добавлен массив с категориями
$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

// массив с объявлениями
$adverts = [

    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'url' => '/img/lot-1.jpg'

    ],

    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'url' => '/img/lot-2.jpg'

    ],

    [
        'name' => '<Крепления Union Contact Pro 2015 года размер L/XL>',
        'category' => 'Крепления',
        'price' => 8000,
        'url' => '/img/lot-3.jpg'

    ],

    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'url' => '/img/lot-4.jpg'

    ],

    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'url' => '/img/lot-5.jpg'

    ],

    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'url' => '/img/lot-6.jpg'

    ],
];


// форматирование суммы цены
function price_format($num) {
    $num = ceil($num); // округляем
    if ($num >= 1000) {
        $num = number_format($num, 0, ',', ' '); // форматируем число
    }

    $num = $num." ₽"; // добавляем рубли
    return $num;
};

// функция подключения темплейтов
function include_template($file_name, $data) {
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

//подключаем темплейты
$content = include_template('main.php',
    [
        'adverts' => $adverts,
        'category' => $category
    ]);

$layout = include_template('layout.php', 
    [
        'content' => $content,
        'title' => 'YetiCave',
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'category' => $category     
    ]);

print($layout);