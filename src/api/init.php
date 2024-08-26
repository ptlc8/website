<?php
// initialisation globales + BDD
include('credentials.php');
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
	echo 'Erreur de connexion côté serveur, veuillez réessayer plus tard';
	exit;
}

// fonction de requête BDD
function request_database(...$request_frags) {
	$request = '';
	$var = false;
	foreach ($request_frags as $frag) {
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

// connexion à un compte avec la session
function login_from_session() {
	session_start();
	if (!isset($_SESSION['username'], $_SESSION['password']))
		return null;
    return request_database("SELECT * FROM USERS WHERE `name` = '", $_SESSION['username'], "' and `password` = '", $_SESSION['password'], "'")->fetch_assoc();
}

// connexion à un compte avec un nom d'utilisateur et un mot de passe
function login($username, $password) {
	session_start();
	$password = hash('sha512', $password);
    $user = request_database("SELECT * FROM USERS WHERE `name` = '", $username, "' and `password` = '", $password, "'")->fetch_assoc();
	if ($user !== null) {
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
	}
	return $user;
}

// générer un token
function generate_token($length=32) {
	$token = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
	for ($i = 0; $i < $length; $i++)
		$token .= $chars[rand(0, strlen($chars) - 1)];
	return $token;
}

// déconnexion
function logout() {
	session_start();
	unset($_SESSION['username'], $_SESSION['password']);
}
?>