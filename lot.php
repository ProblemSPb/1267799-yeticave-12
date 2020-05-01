<?php

require_once('functions/functions.php');
require_once('functions/db_functions.php');

//$id = $_GET['id'];

$con = db_connect();

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$current_lot_page = include_template('current_lot.php',
    [
        'categories' => $categories
]); 

// if(!isset($id)) {
//     //
// } else {
//     print($current_lot_page);
// }

print($current_lot_page);