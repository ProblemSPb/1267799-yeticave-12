<?php

function db_connect() {
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

return $con;
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