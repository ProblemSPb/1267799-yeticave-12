<?php

require_once('settings.php');
require_once "vendor/autoload.php";


// получение лотов без победителей, дата истечения которых <= сейчас
$sql_lots_wo_winner = "SELECT lot.id as lot_id, lot.name as lot_name, user.email as winner_email, user.name as winner_name, user.id as winner_id
                    FROM lot
                    JOIN user on user.id = (SELECT userID FROM bid WHERE lotID = lot.id ORDER BY bid_date DESC LIMIT 1)
                    WHERE winnerID IS NULL AND end_date <= NOW()";
$lots_wo_winner = sql_query_result($con, $sql_lots_wo_winner);

// записываем авторов последних ставок как победителей в таблицу lot
foreach ($lots_wo_winner as $key => $value) {

    $winner_id = $value['winner_id'];
    $lot_id = $value['lot_id'];
    $winner_name = $value['winner_name'];
    $lot_name = $value['lot_name'];
    $winner_email = $value['winner_email'];

    $update_winner = mysqli_query($con, "UPDATE lot SET winnerID = $winner_id WHERE id = $lot_id");

    // если запись победителей прошла успешно, отправляем имейлы
    if ($update_winner) {

        // подключаем email темплейт
        $content = include_template(
            'email.php',
            [
                'winner_name' => $winner_name,
                'lot_id' => $lot_id,
                'lot_name' => $lot_name,
                'host' => $_SERVER["HTTP_HOST"]
            ]
        );

        // Конфигурация транспорта
        $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
            ->setUsername('keks@phpdemo.ru')
            ->setPassword('htmlacademy');

        // Формирование сообщения
        $message = new Swift_Message("Ваша ставка победила");
        $message->setTo([$winner_email => $winner_name]);
        $message->setBody($content);
        $message->setFrom(["keks@phpdemo.ru" => 'YetiCave']);

        // Отправка сообщения
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);

    }
}
