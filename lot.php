<?php

require_once('functions/include_template.php');
require_once('functions/price_format.php');
require_once('functions/db_connect.php');
require_once('functions/sql_query_result.php');

$id = $_GET['id'];

$con = db_connect();

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$current_lot_page = include_template('current_lot.php',
    [
        'categories' => $categories
]); 

if(!isset($id)) {
    //
} else {
    print($current_lot_page);
}