<?php

// получение данных из БД
function sql_query_result($db_connect, $sql_query) {
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_resul_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_resul_array;
}

// блок тестирования массива данных из БД
// foreach($lots as $lot){
//     print($lot['category']);
// }