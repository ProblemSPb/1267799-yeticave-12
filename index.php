<?php

date_default_timezone_set('Europe/Berlin');

$is_auth = rand(0, 1);
$user_name = 'Lena'; // укажите здесь ваше имя
$title = 'YetiCave';

// подключение к серверу
$con = mysqli_connect("localhost", "root", "yourpasswd", "yeticave");

// блок тестировния подключения
// if ($con == false) {
//     print("Ошибка подключения: " . mysqli_connect_error());
// }
// else {
//     print("Соединение установлено"); 
// }

mysqli_set_charset($con, "utf8");

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$sql_result = mysqli_query($con, $sql_category);
// помещение полученных данных в массив
$categories = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

// получение лотов из БД
$sql_lots = "SELECT lot.name, lot.start_price as price, lot.img_link as url, lot.end_date as expire, category.name as category FROM lot LEFT JOIN category ON lot.categoryID = category.ID ORDER BY create_date DESC";
$sql_lots_result = mysqli_query($con, $sql_lots);
$lots = mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);

// блок тестирования массива данных из БД
// foreach($lots as $lot){
//     print($lot['category']);
// }


// расчет времени до конца аукциона
function auction_end($auction_end_date) {
    $time_diff = strtotime($auction_end_date) - time();
    $hours = floor($time_diff / 3600);
    $mins = floor(($time_diff % 3600) / 60);
    $time_auc_end = [$hours, $mins];
    return $time_auc_end;

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
        //'adverts' => $adverts,
        'lots' => $lots,
        'categories' => $categories
    ]);

$layout = include_template('layout.php', 
    [
        'content' => $content,
        'title' => 'YetiCave',
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'categories' => $categories
    ]);

print($layout);
