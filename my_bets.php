<?php

session_start();

require_once('settings.php');

$title = 'Мои ставки';
$user_name = "";

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);


// ЕСЛИ ПОЛЬЗОВАТЕЛЬ ЗАЛОГИНЕН
if (isset($_SESSION['user'])) {
    $user_name = strip_tags($_SESSION['user']['name']);
    $user_id = @$_SESSION['user']['user_id'];


} else {  // ЕСЛИ ПОЛЬЗОВАТЕЛЬ НЕ ЗАЛОГИНЕН
    header("Location: index.php");
    exit();
}

// получение ставок пользователя из БД
$sql_format =   "SELECT lot.id, lot.name as name, lot.img_link as url, lot.winnerID as winner, lot.end_date as expire, category.name as category,
                bid.bid_date as bid_date, bid.sum_price, bid.userID as bid_user, user.user_contact
                FROM lot
                LEFT JOIN category on lot.categoryID = category.ID
                LEFT JOIN bid on lot.id = bid.lotID
                LEFT JOIN user on lot.userID = user.id
                WHERE bid.userID = %d
                ORDER BY bid.bid_date desc;";
$sql_bids = sprintf($sql_format, $_SESSION['user']['user_id']);
$bids = sql_query_result($con, $sql_bids);

$content = include_template('my_bets_template.php',
    [
        'bids' => $bids,
        'user_id' => $user_id
    ]
);

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
