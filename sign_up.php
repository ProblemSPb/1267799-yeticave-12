<?php

require_once('settings.php');
require_once('functions/functions.php');
require_once('functions/db_functions.php');
require_once('functions/validation.php');

$is_auth = 0;
$title = 'Регистрация';
$user_name = 'Lena';

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);



// если форма отправлена
if ($_SERVER['REQUEST_METHOD']=== 'POST') {
 
    $errors = [];

    // применение правил проверок
    $rules = [
        'email' => function() {
            return validateEmail($_POST['email']);
        },
        'name' => function() {
            return validateCategory($_POST['category']);
        },
        'password' => function() {
            return validateText($_POST['description'], 10, 1000);
        },
        'user_contact' => function() {
            return validateNum($_POST['start_price']);
        }
    ];

    // заполняем массив ошибок
    foreach($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

     // финальный массив с ошибками
     $errors = array_filter($errors);

    // Если в отправленной форме ошибки -> снова показать форму + ошибки
    $content = include_template('sign_up_templ.php', 
        [
            'errors' => $errors
        ]
    );

    // если ошибок нет, записать ЛОТ в БД
    //if(empty($errors)) {

        //запись данных из формы в БД -> таблица lot 
        // $stmt = $con->prepare("INSERT INTO lot (create_date, name, description, img_link, start_price, end_date, bid_step, userID, winnerID, categoryID) VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, 2, ?)");
        // $stmt->bind_param("sssisii", $_POST['name'], $_POST['description'], $file_url, $_POST['start_price'], $_POST['end_date'], $_POST['bid_step'], $_POST['category']);
        // $stmt_result = $stmt->execute();
        // $stmt->close();

        // if($stmt_result) {
        //     // переадресация на страницу созданного лота
        //     $new_lot_id = mysqli_insert_id($con);
        //     header("Location: /lot.php?id=".$new_lot_id);
        // } else {
        //     print(mysqli_error($con));
        // } 

        // $con->close();
    //}

} else {

    $content = include_template('sign_up_templ.php', 
    [
        'categories' => $categories
    ]
    );

}

// подключение лейаута и контента 
$layout = include_template('page_layout.php', 
[
    'content' => $content,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title
]);

print($layout);