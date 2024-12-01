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

$default = $_REQUEST['d'] ?? 'robohash';

# See https://docs.gravatar.com/api/avatars/images/ for more information
exit(header("Location: https://www.gravatar.com/avatar/".md5(strtolower(trim($user['email'])))."?s=200&d=".$default."&r=pg"));

?>