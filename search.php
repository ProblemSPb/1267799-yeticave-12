<?php

session_start();

require_once('settings.php');

$title = "Результаты поиска";
$user_name = "";

// если пользователь уже залогинен
if (isset($_SESSION['user'])) {
    $user_name = strip_tags($_SESSION['user']['name']);
}

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$lots = [];
$not_found = "";

$content = include_template('not_found.php');

// если отправлен запрос на поиск
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $search = trim($_GET['search']);

    // извлекаем из URL текущую страницу
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = intval($_GET['page']);
    }

    // если строка запроса непустая
    if (!empty($search)) {

        //считаем количество записей по запросу
        $sql_count_query = "SELECT COUNT(id) as count FROM lot WHERE MATCH(lot.name, lot.description) AGAINST('%s') AND lot.end_date > NOW()";
        $sql_count = sprintf($sql_count_query, $search);
        $result = sql_query_result($con, $sql_count);
        $count = $result[0]['count'];

        // количество лотов на странице
        $limit = 9;

        // считаем общее количество страниц
        $pages_total = intval(($count - 1) / $limit) + 1;

        // если значени $page меньше 1, переходим на первую страницу выдачи результатов поиска
        // а если слишком большое, на последнюю
        if (empty($page) or $page < 0) {
            $page = 1;
        } elseif ($page > $pages_total) {
            $page = $pages_total;
        }

        // находим, с какого лота выводить на странице результаты
        $offset = (intval($page) - 1) * $limit;

        // если ничего не найдено
        if (!$count == 0) {
            // получение соответствующих лотов из БД
            $sql_format =   "SELECT lot.id, lot.name, lot.start_price as price, lot.img_link as url, lot.end_date as expire, category.name as category
                            FROM lot
                            LEFT JOIN category ON lot.categoryID = category.ID
                            WHERE MATCH(lot.name, lot.description) AGAINST('%s')
                            AND lot.end_date > NOW()
                            ORDER BY create_date
                            LIMIT %d
                            OFFSET %d";
            $sql_lots = sprintf($sql_format, $search, $limit, $offset);
            $lots = sql_query_result($con, $sql_lots);

            $content = include_template( // показать лоты
                'search_template.php',
                [
                    'lots' => $lots,
                    'categories' => $categories,
                    'page' => $page,
                    'search' => $search,
                    'pages_total' => $pages_total
                ]
            );
        }
    }
}

// подключение лейаута и контента 
$layout = include_template(
    'page_layout.php',
    [
        'content' => $content,
        'categories' => $categories,
        'user_name' => $user_name,
        'title' => $title
    ]
);

print($layout);
