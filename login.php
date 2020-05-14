<?php

require_once('settings.php');

$title = "Вход";

$is_auth = "";
if ($is_auth == 1) {
    header("Location: index.php");
}
$user_name = 'Lena';

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// если пользователь уже залогинен
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    // применение правил проверок
    $rules = [
        'email' => function () {
            return validateEmail($_POST['email']);
        },
        'password' => function () {
            return validateNotEmpty($_POST['password']);
        }
    ];

    // заполняем массив ошибок
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    // Сравнить введенные имейл и пароль с БД
    $stmt = $con->prepare("SELECT password FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $_POST['email']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $password);

    // если email найден
    if (mysqli_stmt_fetch($stmt)) {
        // echo $_POST['password'];
        // echo $password;
        

        // проверка пароля
        $input = $_POST['password'];
        if(password_verify($input, $password)) {
            echo 'Password is valid!';
        } else {
            $errors['password'] = "Неверный пароль";
        }

        

        // if (!(password_verify($input, $password))) {
        //     $errors['password'] = "Неверный пароль";
        // } else {
        //     echo "correct password";
        // }
        // если имейл не найден
    } else {
        $errors['email'] = "Пользователь с таким email не найден";
    }


    // финальный массив с ошибками
    $errors = array_filter($errors);


    // Если в отправленной форме ошибки -> снова показать форму + ошибки
    $content = include_template(
        'login_template.php',
        [
            'errors' => $errors
        ]
    );


    if (empty($errors)) {

// ?????

    }
} else {

    $content = include_template('login_template.php');
}

// подключение лейаута и контента 
$layout = include_template(
    'page_layout.php',
    [
        'content' => $content,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title
    ]
);

print($layout);


  ////////// Почему-то не работает, если поле пустое, не выводит валидацию по пустому полю
  ///////// $con -  подключение к БД недоступно из функции в файле валидации -> пришлось вынести ее в login.php
