<?php

// установка временной зоны
// подключение функций
// подключение к БД

date_default_timezone_set('Europe/Berlin');

require_once('functions/functions.php');
require_once('functions/db_functions.php');
require_once('functions/validation.php');

$con = db_connect($db_config);
