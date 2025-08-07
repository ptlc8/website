<?php

if (!isset($_REQUEST['token'])) {
    exit('need token');
}

$token = $_REQUEST['token'];

include('init.php');
$result = request_database('SELECT id, name FROM `USERS` JOIN `TOKENS` ON `USERS`.`id` = `TOKENS`.`user` WHERE `token` = ', $token);
$user = $result->fetch_assoc();

if ($user == null) {
    exit('invalid token');
}

echo json_encode($user);

?>