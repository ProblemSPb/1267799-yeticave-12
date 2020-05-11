<?php

// расчет времени до конца аукциона
function auction_end($auction_end_date) {
    $time_diff = strtotime($auction_end_date) - time();
    $hours = floor($time_diff / 3600);
    $mins = floor(($time_diff % 3600) / 60);
    $time_auc_end = [$hours, $mins];
    return $time_auc_end;

};

// функция подключения темплейтов
function include_template($file_name, $data = array()) {
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

// форматирование суммы цены
function price_format($num) {
    $num = ceil($num); // округляем
    if ($num >= 1000) {
        $num = number_format($num, 0, ',', ' '); // форматируем число
    }

    $num = $num." ₽"; // добавляем рубли
    return $num;
};

//сохранение значений полей
function getPostValue($name) {
    return $_POST[$name] ?? "";
}

// ВАЛИДАЦИЯ ПОЛЕЙ ФОРМЫ

function validateNotEmpty($field) {

    if(empty($field)){
        return 'Это поле должно быть заполнено';
    }

}

// проверка категории
function validateCategory($category) {

    if ($category <= 0) {
        return "Категория не выбрана";
    }
}

// проверка длины вводимого значения
function validateText($name, $min, $max) {

    validateNotEmpty($name);
    
    if(mb_strlen($name)<$min) {
        return  "Это поле должно быть больше " . $min . " символов";
    } elseif(mb_strlen($name) > $max) {
        return "Это поле должно быть меньше " . $max . " символов";
    }

}

// проверка формата цены и шага ставки
function validateNum($num) {

    validateNotEmpty($num);

    // целое число
    if(!(ctype_digit($num))) {
        return "Введите целое число больше 0";
    }

    if($num <= 0) {
        return "Значение должно быть больше нуля";
    }

}

function validateImg() {

    // проверка на заполнение
    if(empty($_FILES['lot_img']['name'])) {
        return "Загрузите картинку товара";
    } else {
        //формат
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
        $detectedType = exif_imagetype($_FILES['lot_img']['tmp_name']);

        if(!in_array($detectedType, $allowedTypes)){
            return "Загрузите картинку в формате jpg/jpeg или png";
        }

        $file_size = $_FILES['lot_img']['size'];
        if($file_size > 5000000) {
            return "Файл не должен быть больше 5мб";
        }
    }
}

function is_date_valid(string $date) {

    validateNotEmpty($date);

    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    if ($dateTimeObj !== false && array_sum(date_get_last_errors()) === 0) {
        $expect_auc_end = (strtotime($date) - strtotime('now')) / 3600;
        if($expect_auc_end <= 24) {
            return "Окончание аукциона должно быть не раньше 24 часов";
        }
    }
    else {
        return "Укажите дажу окончания аукциона";
    }
}

