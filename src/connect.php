<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Connecter | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body <?php if (isset($_REQUEST['background'])) { ?>class="custom-background" style="background-image: url('<?=addslashes($_REQUEST['background'])?>');"<?php } ?>>
		<section class="floating container">
			<?php
			include("api/init.php");
			if (!isset($_REQUEST['app'])) { ?>
			<div>
				<h1>Mmh... Cette page a été mal appelée 🤯</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil </a>
			</div>
			<?php } else if (($app = sendRequest("SELECT * FROM APPS WHERE id = '", $_REQUEST['app'], "';")->fetch_assoc()) == null) { ?>
				<h1>Euh... Cette application n'existe pas 🤓</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil</a>
			<?php } else if (($user = login()) == null) {
				header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI']));
			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$token = generateToken(16);
				sendRequest("INSERT INTO `TOKENS` (`token`, `user`, `app`) VALUES ('", $token, "', '", $user['id'], "', '", $_REQUEST['app'], "') ON DUPLICATE KEY UPDATE `token` = '", $token, "';");
				header('Location: '.$app['returnUrl'].$token.'&'.$_REQUEST['params']);
				?>
				<div>
					<h1><u><?=htmlspecialchars($app['name'])?></u> connecté</h1>
					<p class="helper">Cette application a été connecté(e) à ton compte. 🎉</p>
				</div>
			<?php } else { ?>
			<form method="post" action="">
				<h1>Connecter <u><?=htmlspecialchars($app['name'])?></u> à ton compte</h1>
				<div class="connection">
					<?=htmlspecialchars($user['name'])?>
					<img width="50" src="avatar.php">
					🔗
					<img width="50" src="<?= $app['icon'] ?? '' ?>" />
					<?=htmlspecialchars($app['name'])?>
				</div>
				<span class="helper"><u><?=htmlspecialchars($app['name'])?></u> aura accès à :</span>
				<ul>
					<li>ton nom d'utilisateur et ton id</li>
					<!--<li>ton adresse e-mail</li>-->
				</ul>
				<input type="submit" value="Connecter" class="good" />
			</form>
			<?php } ?>
			<a href="account.php">Mon compte</a>
			<a href="login.php?go=<?=urlencode($_SERVER['REQUEST_URI'])?>">Changer de compte</a>
		</section>
		<?php if (isset($app)) { ?><div class="custom-background" style="background-image: url('<?=addslashes($app['background'])?>');"></div><?php } ?>
	</body>
</html>
