<?php

date_default_timezone_set('Europe/Berlin');

require_once('functions/include_template.php');
require_once('functions/auction_end.php');
require_once('functions/price_format.php');
require_once('functions/db_connect.php');
require_once('functions/sql_query_result.php');

$is_auth = rand(0, 1);
$user_name = 'Lena'; // укажите здесь ваше имя
$title = 'YetiCave';


$con = db_connect();

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// получение лотов из БД
$sql_lots = "SELECT lot.name, lot.start_price as price, lot.img_link as url, lot.end_date as expire, category.name as category FROM lot LEFT JOIN category ON lot.categoryID = category.ID ORDER BY create_date DESC";
$lots = sql_query_result($con, $sql_lots);


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
