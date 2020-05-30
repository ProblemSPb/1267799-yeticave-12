<?php

$config_file = 'config.php';

/**
 * Проверка на наличие config.php
 * @param $config_file Путь к файлу конфига
 * 
 * @return string Возвращает параметры для подключения или выходит с сообщением ошибки
 */
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

/**
 * Получает данные из БД
 * @param $db_connect Данные для подключения к БД
 * @param sql_query SQL запрос
 * 
 * @return array Возвращает результаты SQL запроса
 */
function sql_query_result($db_connect, $sql_query) {
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_resul_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_resul_array;
};
