<?php

$is_auth = rand(0, 1);
$user_name = 'Lena';

require_once('functions/functions.php');
require_once('functions/db_functions.php');

$id = $_GET['id'];

$con = db_connect();

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// проверяем, если запрошенный id лота существует в БД
$sql_lot = "SELECT lot.*, category.name as 'category name' FROM lot  INNER JOIN category on lot.categoryID = category.ID WHERE lot.id = $id";
$lot_id = mysqli_query($con, $sql_lot);
$row_count = mysqli_num_rows($lot_id);

// если существует -> показать лот
// если нет -> 404
if($row_count != null){
    
    // данные по лоту 
    $lot_data = sql_query_result($con, $sql_lot);
    
    // подключаем темплейт
    $current_lot_page = include_template('current_lot.php',
    [
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'lot' => $lot_data
    ]); 

    print($current_lot_page);

} else {
    $page_not_found = include_template('404.php',
    [
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name
]); 
    print($page_not_found);
}

