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

}