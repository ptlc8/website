<?php
$valid_request = false;

include("api/init.php");
if (isset($_REQUEST['app'])) {
	$valid_request = true;
	$app = request_database("SELECT * FROM APPS WHERE id = '", $_REQUEST['app'], "';")->fetch_assoc();
	if ($app !== null) {
		$user = login_from_session();
		if ($user === null)
			exit(header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI'])));
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$token = generate_token(16);
			request_database("INSERT INTO `TOKENS` (`token`, `user`, `app`) VALUES ('", $token, "', '", $user['id'], "', '", $_REQUEST['app'], "') ON DUPLICATE KEY UPDATE `token` = '", $token, "';");
			$connected = true;
			$params = $_REQUEST['params'] ?? '';
			exit(header('Location: '.$app['returnUrl'].$token.'&'.$params));
		} else {
			$connected = false;
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Connexion | <?= htmlspecialchars(get_site_name()) ?></title>
		<link rel="stylesheet" href="style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<?php if (isset($app)) { ?>
			<div class="custom-background" style="background-image: url('<?= addslashes($app['background']) ?>');"></div>
		<?php } ?>
		<section class="floating container">
			<?php
			if (!$valid_request) { ?>
				<h1>Mmh... Cette page a été mal appelée 🤯</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil </a>
			<?php } else if ($app === null) { ?>
				<h1>Euh... Cette application n'existe pas 🤓</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil</a>
			<?php } else if ($connected) { ?>
				<h1><u><?= htmlspecialchars($app['name']) ?></u> connecté</h1>
				<p class="helper">Cette application a été connecté(e) à ton compte. 🎉</p>
			<?php } else { ?>
				<form method="post" action="">
					<h1>Connecter <u><?= htmlspecialchars($app['name']) ?></u> à ton compte</h1>
					<div class="connection">
						<?= htmlspecialchars($user['name']) ?>
						<img width="50" src="avatar.php">
						🔗
						<img width="50" src="<?= $app['icon'] ?? '' ?>" />
						<?= htmlspecialchars($app['name']) ?>
					</div>
					<span class="helper"><u><?= htmlspecialchars($app['name']) ?></u> aura accès à :</span>
					<ul>
						<li>ton nom d'utilisateur et ton id</li>
						<!--<li>ton adresse e-mail</li>-->
					</ul>
					<input type="submit" value="Connecter" class="good" autofocus />
				</form>
			<?php } ?>
			<a href="account.php">👤 Mon compte</a>
			<a href="login.php?go=<?= urlencode($_SERVER['REQUEST_URI']) ?>">🔁 Changer de compte</a>
		</section>
	</body>
</html>
