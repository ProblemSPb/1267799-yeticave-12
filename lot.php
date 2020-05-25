<?php
session_start();

require_once('settings.php');

$user_name = "";

if (isset($_SESSION['user'])) {
    $user_name = strip_tags($_SESSION['user']['name']);
}

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$errors = [];

$content = include_template('404.php');
$title = '404 Страница не найдена';

//проверка параметра из строки запроса
if (isset($_GET['id']) && $_GET['id'] > 0) {

    $id = intval($_GET['id']);

    // данные по лоту 
    $sql_format = "SELECT lot.*, category.name as 'category name' FROM lot  INNER JOIN category on lot.categoryID = category.ID WHERE lot.id = %d AND lot.end_date > NOW()";
    $sql_lot = sprintf($sql_format, $id);
    $lot_data = sql_query_result($con, $sql_lot);

    // данные по ставкам
    $sql_bids_format = "SELECT bid.*, user.name FROM bid LEFT JOIN user on bid.userID = user.id WHERE bid.lotID = %d ORDER BY bid.bid_date DESC";
    $sql_bids = sprintf($sql_bids_format, $id);
    $bids_data = sql_query_result($con, $sql_bids);

    // считаем количество ставок по лоту
    $result = mysqli_query($con, $sql_bids);
    $num_rows = mysqli_num_rows($result);

    // если существует -> показать лот
    // если нет -> 404
    if ($lot_data != null) {

        // если ставок на лот не было, то по дифолту это изначальная цена
        $sql_last_bid_format = "SELECT sum_price, userID FROM bid WHERE lotID = %d ORDER BY id DESC LIMIT 1";
        $sql_last_bid = sprintf($sql_last_bid_format, $id);
        $last_bid_result = sql_query_result($con, $sql_last_bid);

        $last_bid_user =  0;

        if (empty($last_bid_result)) {
            $last_bid = $lot_data[0]['start_price'];
        } else {
            $last_bid = $last_bid_result[0]['sum_price'];

            // получение ID пользователя, который сделал последнюю ставку на текущий момент
            $last_bid_user =  $last_bid_result[0]['userID'];
        }

        // определяем, закончился ли аукцион для товара
        // если да, меняем значение статуса на true
        $auc_end_status = false;
        $today = new DateTime();
        $lot_end = new DateTime($lot_data[0]['end_date']);

        if ($lot_end < $today) {
            $auc_end_status = true;
        }

        // ФОРМА СТАВКИ ЕСЛИ ПОЛЬЗОВАТЕЛЬ ЗАЛОГИНЕН
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            // проверить, что поле ставки заполнено в корректном формате
            $errors['cost'] = validateBid($_POST['cost'], $lot_data[0]['bid_step']);

            // финальный массив с ошибками
            $errors = array_filter($errors);

            if (empty($errors)) {

                // посчитать общую стоимость вмместе с новой ставкой
                $sum_price = $_POST['cost'] + $last_bid;

                //запись данных из формы в БД -> таблица bid 
                $stmt = $con->prepare("INSERT INTO bid (bid_date, sum_price, userID, lotID) VALUES (NOW(), ?, ?, ?)");
                $stmt->bind_param("iii", $sum_price, $_SESSION['user']['user_id'], $id);
                $stmt_result = $stmt->execute();
                $stmt->close();

                // переадресация на обновленную страницу с добавленной новой ставкой и новой ценой
                header("Location: lot.php?id=$id");

                if (!$stmt_result) {
                    print(mysqli_error($con));
                }

                $con->close();
            }
        }

        $content = include_template(
            'lot_template.php',
            [
                'lot' => $lot_data[0],
                'errors' => $errors,
                'bids' => $bids_data,
                'last_bid' => $last_bid,
                'num_rows' => $num_rows,
                'last_bid_user' => $last_bid_user,
                'auc_end_status' => $auc_end_status
            ]
        );
        $title = $lot_data[0]['name'];
    }
}

// подключаем лейаут
$lot_layout = include_template(
    'page_layout.php',
    [
        'content' => $content,
        'categories' => $categories,
        'user_name' => $user_name,
        'title' => $title
    ]
);

print($lot_layout);
