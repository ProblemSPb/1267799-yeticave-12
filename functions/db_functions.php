<?php

$config_file = 'config.php';

// проверка существования config.php
if(file_exists($config_file)){
    
    require_once($config_file);

    function db_connect($db_config) {
        // подключение к серверу
    $con = mysqli_connect($db_config['db_host'], $db_config['db_username'], $db_config['db_password'], $db_config['db_name']);
    mysqli_set_charset($con, "utf8");
    
    return $con;
    };
} else {
    exit("Файл config.php не найден");
};



// получение данных из БД
function sql_query_result($db_connect, $sql_query) {
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_resul_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_resul_array;
};

// блок тестирования массива данных из БД
// foreach($lots as $lot){
//     print($lot['category']);
// }


// блок тестировния подключения
    // if ($con == false) {
    //     print("Ошибка подключения: " . mysqli_connect_error());
    // }
    // else {
    //     print("Соединение установлено"); 
    // }
    