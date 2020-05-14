<?php

require_once('settings.php');

$title = 'Регистрация';

$is_auth = "";
if($is_auth == 1) {
    header("Location: index.php");
}
$user_name = 'Lena';

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $errors = [];

    // применение правил проверок
    $rules = [
        'email' => function() {
            return validateEmail($_POST['email']);
        },
        'name' => function() {
            return validateText($_POST['name'], 2, 40);
        },
        'password' => function() {
            return validatePass($_POST['password']);
        },
        'user_contact' => function() {
            return validateText($_POST['user_contact'], 6, 25);
        }
    ];

    // заполняем массив ошибок
    foreach($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    // проверка если такой email уже зарегестрирован
    $stmt = $con->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $stmt_result = mysqli_stmt_get_result($stmt);
    $stmt->close();

    if(mysqli_num_rows($stmt_result)) {
        $errors['email'] = "Пользователь с таким email уже существует";
    }


     // финальный массив с ошибками
     $errors = array_filter($errors);

    // Если в отправленной форме ошибки -> снова показать форму + ошибки
    $content = include_template('sign_up_templ.php', 
        [
            'errors' => $errors
        ]
    );

    // если ошибок нет, записать юзера в БД
    if(empty($errors)) {

        // запись данных из формы в БД -> таблица user 
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO user (register_date, email, name, password, user_contact) VALUES (NOW(), ?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['email'], $_POST['name'], $hashed_pass, $_POST['user_contact']);
        $stmt_result = $stmt->execute();
        $stmt->close();

        if($stmt_result) {
            // переадресация на страницу логина
            header("Location: /login.php");
        } else {
            print(mysqli_error($con));
        } 

        $con->close();
    }

} else {

    $content = include_template('sign_up_templ.php');

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