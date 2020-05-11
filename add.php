<?php

require_once('settings.php');
require_once('functions/functions.php');
require_once('functions/db_functions.php');

$is_auth = rand(0, 1);
$user_name = 'Lena';
$title = 'Добавление лота';


// получение категорий из БД
$sql_category = "SELECT id, name, code_name FROM category";
$categories = sql_query_result($con, $sql_category);

// если форма отправлена
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

    // заполняем массив ошибок
    foreach($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    // проверка поля загрузки файла
    $file_error = validateImg();

    if(!(empty($file_error))) {
        $errors['lot_img'] = $file_error;
    }

     // финальный массив с ошибками
     $errors = array_filter($errors);

    // Если в отправленной форме ошибки -> снова показать форму + ошибки
    $content = include_template('add_lot.php', 
        [
            'categories' => $categories,
            'errors' => $errors
        ]
    );

    // если ошибок нет, записать ЛОТ в БД
    if(empty($errors)) {

        // загрузка файлa
        if(isset($_FILES['lot_img'])) {
            $file_name = $_FILES['lot_img']['name'];
            $file_path = __DIR__.'/uploads/';
            $file_url = '/uploads/'.$file_name; // path to a file in uploads

            move_uploaded_file($_FILES['lot_img']['tmp_name'], $file_path.$file_name);
        }

        //запись данных из формы в БД -> таблица lot 
        $stmt = $con->prepare("INSERT INTO lot (create_date, name, description, img_link, start_price, end_date, bid_step, userID, winnerID, categoryID) VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, 2, ?)");
        $stmt->bind_param("sssisii", $_POST['name'], $_POST['description'], $file_url, $_POST['start_price'], $_POST['end_date'], $_POST['bid_step'], $_POST['category']);
        $stmt_result = $stmt->execute();
        $stmt->close();

        if($stmt_result) {
            // переадресация на страницу созданного лота
            $new_lot_id = mysqli_insert_id($con);
            header("Location: /lot.php?id=".$new_lot_id);
        } else {
            print(mysqli_error($con));
        } 

        $con->close();
    }

} else {
// если форму еще не отправляли, то показать пустую форму для заполнения
    $content = include_template('add_lot.php', 
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


////
//// КАТЕГОРИЯ НЕ ПОКАЗЫВАЕТ ERROR MESSAGE
//// КАТЕГОРИЯ НЕ ЗАПОМИНАЕТ ЗНАЧЕНИЕ
//// КАРТИНКА ПРОПАДАЕТ ЕСЛИ БЫЛИ ОШИБКИ В ДРУГИХ ПОЛЯХ, но не в картинке
//// НЕ НРАВИТСЯ КУСОК php в add_lot.php в шапке, не придумала, куда его впихнуть еще