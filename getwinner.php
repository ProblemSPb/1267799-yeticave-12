<?php

require_once('settings.php');
require_once "vendor/autoload.php";

// получение лотов без победителей, дата истечения которых <= сейчас
$sql_lots_wo_winner = "SELECT lot.id as lot_id, lot.name as lot_name, user.email as winner_email, user.name as winner_name, user.id as winner_id
                    FROM lot
                    INNER JOIN bid ON bid.lotID = lot.id
                    INNER JOIN user ON user.id = bid.userID
                    WHERE lot.winnerID IS NULL AND lot.end_date <= NOW()
                    ORDER BY bid.bid_date DESC LIMIT 1";
$lots_wo_winner = sql_query_result($con, $sql_lots_wo_winner);

// записываем авторов последних ставок как победителей в таблицу lot
foreach ($lots_wo_winner as $key => $value) {
    $update_winner = mysqli_query($con, "UPDATE lot SET winnerID = " . $value['winner_id'] . " WHERE id = " . $value['lot_id']);

    // если запись победителей прошла успешно, отправляем имейлы
    if ($update_winner) {
        // подключаем email темплейт
        $content = include_template(
            'email.php',
            [
                'winner_name' => $value['winner_name'],
                'lot_id' => $value['lot_id'],
                'lot_name' => $value['lot_name'],
                'host' => $_SERVER["HTTP_HOST"]
            ]
        );

        // Конфигурация транспорта
        $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
            ->setUsername('keks@phpdemo.ru')
            ->setPassword('htmlacademy')
            ->setEncryption(null);

        // // Формирование сообщения
        $message = new Swift_Message("Ваша ставка победила");
        $message->setTo([$value['winner_email'] => $value['winner_name']]);
        $message->setBody($content, "text/html");
        $message->setFrom(["keks@phpdemo.ru" => 'YetiCave']);

        // Отправка сообщения
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    }
}
