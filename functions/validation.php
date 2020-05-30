<?php

/**
 * Сохраняет значений полей POST
 * @param $name Значение, необходимое в массиве POST
 *
 * @return string Возвращает значение, если оно есть
 */
function getPostValue($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Сохраняет значений полей GET
 * @param $name Значение, необходимое в массиве GET
 *
 * @return string Возвращает значение, если оно есть
 */
function getGetValue($name)
{
    return $_GET[$name] ?? "";
}

/////////////////////////
// ФОРМА ДОБАВЛЕНИЯ ЛОТА
////////////////////////

/**
 * Проверяет, если поле пустое
 * @param $field Поле для проверки
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateNotEmpty($field)
{
    $validation = "";
    if (empty($field)) {
        $validation = 'Это поле должно быть заполнено';
    }

    return $validation;
}

/**
 * Проверяет, если поле Категория пустое
 * @param $category Поле для проверки
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateCategory($category)
{
    $validation = "";

    if ($category <= 0) {
        $validation = "Категория не выбрана";
    }
    return $validation;
}

/**
 * Проверяет, если введенное значение в поле меньше минимального количества
 * @param $field Поле для проверки
 * @param int $min Минимальная длина введенного в поле значения
 * @param int $max Максимальная длина введенного в поле значения
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateText($name, int $min, int $max)
{
    validateNotEmpty($name);
    
    $validation = "";

    if (mb_strlen($name)<$min) {
        $validation =  "Это поле должно быть не меньше " . $min . " символов";
    } elseif (mb_strlen($name) > $max) {
        $validation = "Это поле должно быть меньше " . $max . " символов";
    }
    return $validation;
}

/**
 * Проверяет форматы цены и шага ставки: если введенное значение в поле - целое число и > 0
 * @param $num Число для проверки
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateNum($num)
{
    validateNotEmpty($num);

    $validation = "";

    // целое число
    if (!(ctype_digit($num))) {
        $validation = "Введите целое число больше 0";
    }

    if ($num <= 0) {
        $validation = "Значение должно быть больше нуля";
    }

    return $validation;
}

/**
 * Проверяет формат и размер загружаемой картинки
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateImg()
{
    $validation = "";

    // проверка на заполнение
    if (empty($_FILES['lot_img']['name'])) {
        $validation = "Загрузите картинку товара";
    } else {
        //формат
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
        $detectedType = exif_imagetype($_FILES['lot_img']['tmp_name']);

        if (!in_array($detectedType, $allowedTypes)) {
            $validation = "Загрузите картинку в формате jpg/jpeg или png";
        }

        $file_size = $_FILES['lot_img']['size'];
        if ($file_size > 5000000) {
            $validation = "Файл не должен быть больше 5мб";
        }
    }

    return $validation;
}

/**
 * Конвертирует кириллицу в латиницу в названии файла
 *
 * @return string Возвращает название файла в латинице
 */
function translate($string)
{
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

/**
 * Проверяет формат даты - окончания аукциона
 * @param string $date Дата для проверки
 *
 * @return string Информацию об ошибке или пустую строку
 */
function is_date_valid(string $date)
{
    validateNotEmpty($date);

    $validation = "";

    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    if ($dateTimeObj !== false && array_sum(date_get_last_errors()) === 0) {
        $expect_auc_end = (strtotime($date) - strtotime('now')) / 3600;
        if ($expect_auc_end <= 24) {
            $validation = "Окончание аукциона должно быть не раньше 24 часов";
        }
    } else {
        $validation = "Укажите дажу окончания аукциона";
    }

    return $validation;
}

/////////////////////////
// ФОРМА СТАВКИ В ЛОТЕ
////////////////////////

/**
 * Проверяет формат ставки в лоте: если введенное значение в поле - целое число и > 0
 * @param $num Число для проверки
 * @param $minBid Минимальное значение ставки от продавца
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateBid($num, $minBid)
{
    $validation = "";

    if (!(ctype_digit($num))) {
        $validation = "Введите целое число больше 0";
    }

    if ($num < $minBid) {
        $validation = "Ставка должна быть не меньше минимальной";
    }

    return $validation;
}

/////////////////////////
// ФОРМА РЕГИСТРАЦИИ
////////////////////////

/**
 * Проверяет формат введенного email
 * @param $email Введенный пользователем email
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validateEmail($email)
{
    $validation = "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation = "Введите корректный email";
    }

    return $validation;
}

/**
 * Проверяет формат введенного пароля при регистрации
 * Должен содержать цифры, строчные и заглавные буквы
 * @param $pass Введенный пользователем пароль
 *
 * @return string Информацию об ошибке или пустую строку
 */
function validatePass($pass)
{
    validateNotEmpty($pass);

    $validation = "";

    // добавить валидацию
    if (strlen($pass) < 6) {
        $validation = "Пароль должен быть не менее 6 символов и содержать цифры, заглавные и строчные буквы";
    }

    if (strlen($pass) > 40) {
        $validation = "Пароль не должен быть больше 40 символов и содержать цифры, заглавные и строчные буквы";
    }

    if (!((preg_match('/[A-Z]/', $pass)) && (preg_match('/[a-z]/', $pass)) && preg_match('/[0-9]/', $pass))) {
        $validation = "Пароль должен содержать цифры, заглавные и строчные буквы";
    }

    return $validation;
}
