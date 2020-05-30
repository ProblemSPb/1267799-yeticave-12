<?php

/**
 * Расчет времени до конца аукциона
 * @param $auction_end_date Дата окончания аукциона
 *
 * @return array Первый индекс - оставшееся количество часов, второй - минут
 */
function auction_end($auction_end_date)
{
    $time_diff = strtotime($auction_end_date) - time();
    $hours = floor($time_diff / 3600);
    $mins = floor(($time_diff % 3600) / 60);
    $time_auc_end = [$hours, $mins];
    return $time_auc_end;
};

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $file_name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 *
 * @return string Итоговый HTML
 */
function include_template($file_name, $data = array())
{
    $path = 'templates/' . $file_name;
    $result = '';

    // если файл не найден
    if (!file_exists($path)) {
        return $result;
    };

    ob_start();
    extract($data);
    require_once $path;
    $result = ob_get_clean();
    return $result;
};

/**
 * Функция форматирования цены
 * @param $num Число для форматирования
 *
 * @return string Возвращает округленное число с символом рубля
 */
function price_format($num)
{
    $num = ceil($num); // округляем
    if ($num >= 1000) {
        $num = number_format($num, 0, ',', ' '); // форматируем число
    }

    $num = $num . " ₽"; // добавляем рубли
    return $num;
};


/**
 * Функция назначения html класса rates__timer на странице my_bets
 *
 * @param int $hr Количество оставшихся часов до конца аукциона
 * @param $winnerID ID победителя
 * @param int $userID ID залогиненного юзера
 *
 * @return string Значение класса для html тэга, который использован в темплейте
 */
function trClassRatesTimer(int $hr, $winnerID, int $userID)
{
    $tr_class_rates_timer = "";

    if ($hr < 0 && (int)$winnerID === (int)$userID) {
        $tr_class_rates_timer = "rates__item--win";
    } elseif ($hr < 0 && (int)$winnerID !== (int)$userID) {
        $tr_class_rates_timer = "rates__item--end";
    }

    return $tr_class_rates_timer;
}

/**
 * Функция возвращает true если ставка пользователя победила
 * используется в генерации тега <p> с контактными данными в списке ставок
 * @param $winnerID ID победителя
 * @param int $userID ID залогиненного юзера
 *
 * @return bool Если залогиненный юзер и победитель ставки совпадает, вернет true.
 */
function isWinner($winnerID, int $userID)
{
    $isWinner = false;

    if ((int)$winnerID === (int)$userID) {
        $isWinner = true;
    }

    return $isWinner;
}

/**
 * Функция асайнит значения html классу timer на странице my_bets
 * @param int $hr Количество оставшихся часов до конца аукциона
 * @param $winnerID ID победителя
 * @param int $userID ID залогиненного юзера
 *
 * @return string  Значение класса для html тэга, который использован в темплейте
 */
function tdClassTimer(int $hr, $winnerID, int $userID)
{
    $class_timer = "";

    if ((int)$hr === 0) {
        $class_timer = "timer--finishing";
    } elseif ($hr < 0 && (int)$winnerID !== (int)$userID) {
        $class_timer = "timer--end";
    } elseif ($hr < 0 && (int)$winnerID === (int)$userID) {
        $class_timer = "timer--win";
    }

    return $class_timer;
}

/**
 * Функция назначения value для статуса ставки на странице my_bets
 * Значения:
 * Торги окончены
 * Ставка выиграоа
 * Количество часов и минут до окончания
 * @param int $hr Количество оставшихся часов до конца аукциона
 * @param int $min Количество оставшихся минут до конца аукциона
 * @param $winnerID  ID победителя
 * @param int $userID ID залогиненного юзера
 *
 * @return string Возвращает строку Выиграла ставка, проиграла или количество часов и минут до окончания
 */
function asignPastBidValue(int $hr, int $min, $winnerID, int $userID)
{
    $value = "$hr : $min";

    if ($hr < 0 && (int)$winnerID !== (int)$userID) {
        $value = "Торги окончены";
    } elseif ($hr < 0 && (int)$winnerID === (int)$userID) {
        $value = "Ставка выиграла";
    }

    return $value;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Сравнение даты ставки с сегодняшней
 * @param $date Дата окончания аукциона
 *
 * @return string Строку, сколько времени назад была сделана ставка
 */
function compareDates($date)
{
    // получаем сегодняшнюю и вчерашнюю даты в нужном формате для сравнение с датой ставки
    $today = new DateTime();
    $today_format = $today->format('d.m.y');

    $yesterday = new DateTime();
    $yesterday->modify('-1 day');
    $yesterday_format = $yesterday->format('d.m.y');

    // приводим к тому же формату для сравнения
    $bid_date = new DateTime($date);
    $bid_date_format = $bid_date->format('d.m.y');

    // получить часы и минуты ставки
    $hr_bid = date_format($bid_date, 'H');
    $min_bid = date_format($bid_date, 'i');

    // дата и время сделанной ставки
    $output_date = $bid_date_format . " в " . $hr_bid . ":" . $min_bid;

    // сравнение дат
    if ($bid_date_format === $today_format) {

        // получить часы и минуты для сегодняшней даты
        $hr_today = date_format($today, 'H');
        $min_today = date_format($today, 'i');

        // если ставка сделана меньше часа назад
        if ((int)$hr_bid === (int)$hr_today) {
            $min_diff = $min_today - $min_bid;

            // округляем нулевое значение до единицы
            if ((int)$min_diff === 0) {
                $min_diff = 1;
            }

            $min_plural = get_noun_plural_form($min_diff, "минуту", "минуты", "минут");
            $output_date = $min_diff . " " . $min_plural . " назад";
        } else {
            $hr_diff = $hr_today - $hr_bid;
            $min_plural = get_noun_plural_form($hr_diff, "час", "часа", "часов");
            $output_date = $hr_diff . " " . $min_plural . " назад";
        }
        // логика для вчерашних ставок
    } elseif ($bid_date_format === $yesterday_format) {
        $output_date = "Вчера, в " . $hr_bid . ":" . $min_bid;
    }

    return $output_date;
}
