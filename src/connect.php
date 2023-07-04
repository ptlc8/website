<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Connecter | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<section class="floating container">
			<?php
			include("api/init.php");
			if (!isset($_REQUEST['app'])) { ?>
			<div>
				<h1>Mmh... Cette page a été mal appelée 🤯</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil </a>
			</div>
			<?php } else if (($user = login()) == null) {
				header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI']));
			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = generateToken(16);
				sendRequest("INSERT INTO `TOKENS` (`token`, `user`, `app`) VALUES ('", $token, "', '", $user['id'], "', '", $_REQUEST['app'], "') ON DUPLICATE KEY UPDATE `token` = '", $token, "';");
				if (isset($_REQUEST['go'])) {
					header('Location: '.$_REQUEST['go'].$token);
				} ?>
				<div>
					<h1><u><?=htmlspecialchars($_REQUEST['app'])?></u> connecté</h1>
					<p class="helper">Cette application a été connecté(e) à ton compte. 🎉</p>
				</div>
			<?php } else { ?>
			<form method="post" action="">
				<h1>Connecter <u><?=htmlspecialchars($_REQUEST['app'])?></u> à ton compte</h1>
				<div class="connection">
					<?=htmlspecialchars($user['name'])?>
					<img width="50" src="avatar.php">
					🔗
					<img width="50" src="<?= $_REQUEST['icon'] ?? '' ?>" />
					<?=htmlspecialchars($_REQUEST['app'])?>
				</div>
				<span class="helper"><u><?=htmlspecialchars($_REQUEST['app'])?></u> aura accès à :</span>
				<ul>
					<li>ton nom d'utilisateur et ton id</li>
					<li>ton adresse e-mail</li>
				</ul>
				<input type="submit" value="Connecter" />
			</form>
			<?php } ?>
			<a href="account.php">Mon compte</a>
			<a href="login.php?go=<?=urlencode($_SERVER['REQUEST_URI'])?>">Changer de compte</a>
		</section>
	</body>
</html>
