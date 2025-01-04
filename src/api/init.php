<?php
// si le fichier credentials.php existe, on l'inclut
@include('credentials.php');

// obtenir une variable de configuration
function get_config($name) {
	if (defined($name) && !empty(constant($name)))
		return constant($name);
	return getenv($name) ?? null;
}

// obtenir le nom de domaine
function get_host() {
	return $_SERVER['HTTP_HOST'];
}

// obtenir le protocole
function get_protocol() {
	return $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
}

// obtenir le nom du site
function get_site_name() {
	return get_config('SITE_NAME') ?? get_host();
}

// obtenir les informations du site
function get_site_data() {
	$site_data = new stdClass();
	$site_data->description = get_config('SITE_DESCRIPTION') ?? '';
	$site_data->keywords = get_config('SITE_KEYWORDS') ?? '';
	$site_data->author = get_config('SITE_AUTHOR') ?? '';
	$site_data->copyright = '© '.date('Y').' '.$site_data->author.' - Tous droits réservés';
	return $site_data;
}

// obtenir la liste des projets
function get_sitemap() {
	return json_decode(file_get_contents('sitemap.json'));
}

// obtenir une connexion à la base de données
function get_database() {
	global $mysqli;
	if (!isset($mysqli)) {
		$mysqli = new mysqli(get_config('DB_HOST'), get_config('DB_USER'), get_config('DB_PASS'), get_config('DB_NAME'));
		if ($mysqli->connect_errno) {
			exit('Erreur de connexion côté serveur, veuillez réessayer plus tard');
		}
	}
	return $mysqli;
}

// faire une requête à la base de données
function request_database(...$request_frags) {
	$request = '';
	$var = false;
	foreach ($request_frags as $frag) {
		$request .= ($var ? str_replace(array('\\', '\''), array('\\\\', '\\\''), $frag) : $frag);
		$var = !$var;
	}
	$mysqli = get_database();
	if (!$result = $mysqli->query($request)) {
		echo 'Erreur de requête côté serveur, veuillez réessayer plus tard';
		if (str_ends_with(get_host(), 'localhost'))
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

// vérifier si hCaptcha est activé
function use_hcaptcha() {
	return get_config('HCAPTCHA_SECRET') && get_config('HCAPTCHA_SITEKEY');
}

// obtenir la clé du site hCaptcha
function get_hcaptcha_sitekey() {
	return get_config('HCAPTCHA_SITEKEY');
}

// vérifier la réponse hCaptcha
function verify_hcaptcha($response) {
	$hcaptcha_secret = get_config('HCAPTCHA_SECRET');
	$hcaptcha_response = file_get_contents('https://hcaptcha.com/siteverify?secret='.$hcaptcha_secret.'&response='.rawurlencode($response).'&remoteip='.$_SERVER['REMOTE_ADDR']);
	return json_decode($hcaptcha_response)->success;
}
?>