<?php

include("api/init.php");

if (isset($_REQUEST['id'])) {
    $result = sendRequest("SELECT * FROM `USERS` WHERE `id` = '", $_REQUEST['id'], "';");
    if ($result->num_rows == 0)
    $user = null;
    else
        $user = $result->fetch_assoc();
    } else {
    $user = login();
}

if ($user == null) {
    header("Location: assets/unset.png");
    exit;
}

header("Location: https://www.gravatar.com/avatar/".md5(strtolower(trim($user['email'])))."?s=200&d=robohash&r=pg");

?>