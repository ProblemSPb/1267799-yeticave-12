<?php

session_start();

require_once('settings.php');

$title = 'Добавление лота';
$user_name = "";

// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);
$content = include_template('403.php'); 

$errors = [];

// ЕСЛИ ПОЛЬЗОВАТЕЛЬ ЗАЛОГИНЕН
if (isset($_SESSION['user'])) {
    $user_name = strip_tags($_SESSION['user']['name']);

    // если форма отправлена
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // применение правил проверок
        $rules = [
            'name' => function () {
                return validateText($_POST['name'], 5, 100);
            },
            'category' => function () {
                return validateCategory($_POST['category']);
            },
            'description' => function () {
                return validateText($_POST['description'], 10, 1000);
            },
            'start_price' => function () {
                return validateNum($_POST['start_price']);
            },
            'bid_step' => function () {
                return validateNum($_POST['bid_step']);
            },
            'end_date' => function () {
                return is_date_valid($_POST['end_date']);
            }
        ];

        // заполняем массив ошибок
        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        // проверка поля загрузки файла
        $file_error = validateImg();

        if (!(empty($file_error))) {
            $errors['lot_img'] = $file_error;
        }

        // финальный массив с ошибками
        $errors = array_filter($errors);

        // если ошибок нет, записать ЛОТ в БД
        if (empty($errors)) {

            // загрузка файлa
            if (isset($_FILES['lot_img'])) {

                $pathInfo = pathinfo($_FILES['lot_img']['name']);
                $file_name = translate($pathInfo['filename']) . "." . $pathInfo['extension'];
                $file_path = __DIR__ . '/uploads/';
                $file_url = '/uploads/' . $file_name; // path to a file in uploads

                move_uploaded_file($_FILES['lot_img']['tmp_name'], $file_path . $file_name);
            }

            //запись данных из формы в БД -> таблица lot 
            $stmt = $con->prepare("INSERT INTO lot (create_date, name, description, img_link, start_price, end_date, bid_step, userID, categoryID) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisiii", $_POST['name'], $_POST['description'], $file_url, $_POST['start_price'], $_POST['end_date'], $_POST['bid_step'], $_SESSION['user']['user_id'], $_POST['category']);
            $stmt_result = $stmt->execute();
            $stmt->close();

            if ($stmt_result) {
                // переадресация на страницу созданного лота
                $new_lot_id = mysqli_insert_id($con);
                header("Location: /lot.php?id=" . $new_lot_id);
            } else {
                print(mysqli_error($con));
            }

            $con->close();
        }
    }

    $content = include_template(
        'add_lot.php',
        [
            'categories' => $categories,
            'errors' => $errors
        ]
    );
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
