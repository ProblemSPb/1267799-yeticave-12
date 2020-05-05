<?php

require_once('settings.php');


$is_auth = rand(0, 1);
$user_name = 'Lena';

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

//проверка параметра из строки запроса
if(isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // проверяем, если запрошенный id лота существует в БД
    $sql_format = "SELECT lot.*, category.name as 'category name' FROM lot  INNER JOIN category on lot.categoryID = category.ID WHERE lot.id = %d";
    $sql_lot = sprintf($sql_format, $id);

    // данные по лоту 
    $lot_data = sql_query_result($con, $sql_lot);

    // если существует -> показать лот
    // если нет -> 404
    if($lot_data != null) {
        $content = include_template('lot_template.php',
        [
            'lot' => $lot_data[0]
        ]);
        $title = $lot_data[0]['name'];
    } else {
        $content = include_template('404.php');
        $title = '404 Страница не найдена';
    }
} else {
    $content = include_template('404.php');
    $title = '404 Страница не найдена';
}

// подключаем лейаут
$lot_layout = include_template('page_layout.php',
    [
        'content' => $content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title
    ]);

print($lot_layout);