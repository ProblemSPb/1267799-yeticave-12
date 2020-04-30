<?php

// функция подключения темплейтов
function include_template($file_name, $data) {
    $path ='templates/'.$file_name;
    $result = '';

    // если файл не найден
    if(!file_exists($path)) {
        return $result;
    };

    ob_start();
    extract($data);
    require_once $path;
    $result = ob_get_clean();
    return $result;

};