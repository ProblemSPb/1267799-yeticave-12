<?php

require_once('settings.php');


$is_auth = rand(0, 1);
$user_name = 'Lena';

$id = intval($_GET['id']);

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// проверяем, если запрошенный id лота существует в БД
$sql_format = "SELECT lot.*, category.name as 'category name' FROM lot  INNER JOIN category on lot.categoryID = category.ID WHERE lot.id = %d";
$sql_lot = sprintf($sql_format, $id);

// данные по лоту 
$lot_data = sql_query_result($con, $sql_lot);

$lot_content = "";

// если существует -> показать лот
// если нет -> 404
if($lot_data != null) {
    $lot_content = include_template('lot_template.php',
    [
        'lot' => $lot_data,
    ]);
} else {
    $lot_content = include_template('404.php');
};

// подключаем лейаут
$lot_layout = include_template('lot_layout.php',
    [
        'lot_content' => $lot_content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $lot_data[0]['name']
    ]);

print($lot_layout);