<?php

require_once('settings.php');
require_once('functions/functions.php');
require_once('functions/db_functions.php');

$is_auth = rand(0, 1);
$user_name = 'Lena';

if ($_SERVER['REQUEST_METHOD']=== 'POST') {
 
    $errors = [];

    // применение правил проверок
    $rules = [
        'name' => function() {
            return validateText($_POST['name'], 5, 100);
        },
        'category' => function() {
            return validateCategory($_POST['category']);
        },
        'description' => function() {
            return validateText($_POST['description'], 10, 1000);
        },
        'start_price' => function() {
            return validateNum($_POST['start_price']);
        },
        'bid_step' => function() {
            return validateNum($_POST['bid_step']);
        },
        'end_date' => function() {
            return is_date_valid($_POST['end_date']);
        }
    ];


    foreach($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    validateImg(); 

    print_r($errors);

    // переадресация на главную после добавления товара
    //header("Location: /index.php?success=true");
}


// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$content = include_template('add_lot.php', 
    [
        'categories' => $categories
    ]
);
$title = 'Добавление лота';

$layout = include_template('page_layout.php', 
    [
        'content' => $content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title
    ]);

print($layout);


//проверка отправки формы
print_r($_POST);
print_r($_FILES);