<?php
include("init.php");
if (isset($_REQUEST['username'], $_POST['password'])) {
	$user = login($_REQUEST['username'], $_POST['password']);
	if ($user === null) {
		echo("invalid");
	} else {
		echo("logged in");
	}
} else {
    echo("need more args");
}
?>