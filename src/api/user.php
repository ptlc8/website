<?php

if (!isset($_REQUEST['token'])) {
    exit("need token");
}

$token = $_REQUEST['token'];

include("init.php");
$user = sendRequest("SELECT id, name FROM `USERS` JOIN `TOKENS` ON `USERS`.`id` = `TOKENS`.`user` WHERE `token` = '", $token, "';")->fetch_assoc();

if ($user == null) {
    exit("invalid token");
}

echo json_encode($user);

?>