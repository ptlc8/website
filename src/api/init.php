<?php
// initialisation globales + BDD
include('credentials.php');
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
	echo 'Erreur de connexion côté serveur, veuillez réessayer plus tard';
	exit;
}

// fonction de requête BDD
function sendRequest(...$requestFrags) {
	$request = '';
	$var = false;
	foreach ($requestFrags as $frag) {
		$request .= ($var ? str_replace(array('\\', '\''), array('\\\\', '\\\''), $frag) : $frag);
		$var = !$var;
	}
	global $mysqli;
	if (!$result = $mysqli->query($request)) {
		echo 'Erreur de requête côté serveur, veuillez réessayer plus tard';
		if ($_SERVER['SERVER_NAME'] == 'localhost')
			echo " : $request";
		exit;
	}
	return $result;
}

// connexion à un compte
function login($force=false) {
    session_start();
    if (!isset($_SESSION['username'], $_SESSION['password']) || ($userRequest = sendRequest("SELECT * FROM USERS WHERE `name` = '", $_SESSION['username'], "' and `password` = '", $_SESSION['password'], "'"))->num_rows === 0) {
        if ($force)
            exit("not logged");
    	return null;
    } else {
    	$user = $userRequest->fetch_assoc();
    }
    return $user;
}

// générer un token
function generateToken($length=32) {
	$token = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
	for ($i = 0; $i < $length; $i++)
		$token .= $chars[rand(0, strlen($chars))];
	return $token;
}
?>