<?php

include("api/init.php");

if (isset($_REQUEST['id'])) {
    $user = request_database("SELECT * FROM `USERS` WHERE `id` = '", $_REQUEST['id'], "';")->fetch_assoc();
} else {
    $user = login_from_session();
}

if ($user == null) {
    exit(header("Location: assets/unset.png"));
}
exit(header("Location: https://www.gravatar.com/avatar/".md5(strtolower(trim($user['email'])))."?s=200&d=robohash&r=pg"));

?>