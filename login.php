<?php

session_start();

require_once('settings.php');

$title = "Вход";

// если пользователь уже залогинен
if (isset($_SESSION['user'])) {
    $user_name = strip_tags($_SESSION['user']['name']);
    header("Location: index.php");
    exit();
} else {
    $user_name = "";
}

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

$errors = [];

// если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    $errors = array_filter($errors);

    if (empty($errors)) {

        // Сравнить введенные имейл и пароль с БД
        $stmt = $con->prepare("SELECT password, name, email, id FROM user WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $_POST['email']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $password, $name, $email, $id);

        // если email найден
        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($_POST['password'], $password)) { // проверка пароля
                
                $user = array('email' => $email, 'name' => $name, 'user_id' => $id);
                session_start();
                $_SESSION['user'] = $user;
                header('Location: /index.php');
            } else {
                $errors['password'] = "Неверный пароль";
            }
        } else { // если email не найден
            $errors['email'] = "Пользователь с таким email не найден";
        }
    }
}


$content = include_template(
    'login_template.php',
    [
        'errors' => $errors
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

