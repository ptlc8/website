<?php session_start();
include("init.php");
if (isset($_REQUEST['username'], $_POST['password'])) {
	$hashed_password = hash('sha512', $_POST['password']);
	if (sendRequest("SELECT * FROM USERS WHERE `name` = '", $_REQUEST['username'], "' and `password` = '", $hashed_password, "'")->num_rows === 0) {
		echo("invalid");
	} else {
		$_SESSION['username'] = $_REQUEST['username'];
		$_SESSION['password'] = $hashed_password;
		echo("logged in");
	}
} else {
    echo("need more args");
}
?>