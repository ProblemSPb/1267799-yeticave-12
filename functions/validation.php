<?php


//сохранение значений полей
function getPostValue($name) {
    return $_POST[$name] ?? "";
}

function getGetValue($name) {
    return $_GET[$name] ?? "";
}

/////////////////////////
// ФОРМА ДОБАВЛЕНИЯ ЛОТА
//////////////////////// 

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
        return  "Это поле должно быть не меньше " . $min . " символов";
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

// проверка картинки
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

// конвертация кириллицы в латиницу в названии файла
function translate($string) {
    // Замена символов
    $replace = [
      'а' => 'a',   'б' => 'b',
      'в' => 'v',   'г' => 'g',
      'д' => 'd',   'е' => 'e',
      'ё' => 'yo',  'ж' => 'j',
      'з' => 'z',   'и' => 'i',
      'й' => 'y',   'к' => 'k',
      'л' => 'l',   'м' => 'm',
      'н' => 'n',   'о' => 'o',
      'п' => 'p',   'р' => 'r',
      'с' => 's',   'т' => 't',
      'у' => 'u',   'ф' => 'f',
      'х' => 'h',   'ц' => 'ts',
      'ч' => 'ch',  'ш' => 'sh',
      'щ' => 'sch', 'ъ' => '',
      'ы' => 'i',   'ь' => '',
      'э' => 'e',   'ю' => 'ju',
      'я' => 'ja',  ' ' => '-'
    ];

    // Переводим строку в нижний регистр
    $string = mb_strtolower($string, 'utf-8');
    // Заменяем
    $string = strtr($string, $replace);
    // Заменяем все лишние символы и возвращаем
    return preg_replace('~[^a-z\-]~', null, $string);

}
// проверка даты
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

/////////////////////////
// ФОРМА РЕГИСТРАЦИИ
////////////////////////

// валидация email

function validateEmail($email) {

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
}

// валидация пароля
function validatePass($pass) {

    validateNotEmpty($pass);

    // добавить валидацию 
    if(strlen($pass) < 6) {
        return "Пароль должен быть не менее 6 символов и содержать цифры, заглавные и строчные буквы";
    }

    if(strlen($pass) > 40 ) {
        return "Пароль не должен быть больше 40 символов и содержать цифры, заглавные и строчные буквы";
    }

    if(!((preg_match('/[A-Z]/', $pass)) && (preg_match('/[a-z]/', $pass)) && preg_match('/[0-9]/', $pass))) {
        return "Пароль должен содержать цифры, заглавные и строчные буквы";
    }
}

