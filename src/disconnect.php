<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Déconnecter | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<section class="floating container">
			<?php
			include("api/init.php");
			if (!isset($_REQUEST['app'])) { ?>
				<h1>Mmh... Cette page a été mal appelée 🤯</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil </a>
			<?php } else if (($app = sendRequest("SELECT * FROM APPS WHERE id = '", $_REQUEST['app'], "';")->fetch_assoc()) == null) { ?>
				<h1>Euh... Cette application n'existe pas 🤓</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil</a>
			<?php } else if (($user = login()) == null) {
				header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI']));
			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				sendRequest("DELETE FROM `TOKENS` WHERE `user` = '", $user['id'], "' AND app = '", $_REQUEST['app'], "';");
				if (isset($_REQUEST['go'])) {
					header('Location: '.$_REQUEST['go']);
				} else if (isset($_REQUEST['closeafter'])) { ?>
					<script>window.close();</script>
				<?php } else if (isset($_REQUEST['back'])) { ?>
					<script>window.history.go(-2);</script>
				<?php } ?>
				<h1><u><?=htmlspecialchars($app['name'])?></u> déconnecté</h1>
				<p class="helper">Cette application a été déconnecté de ton compte. 😢</p>
			<?php } else { ?>
			<form method="post" action="">
				<h1>Déconnecter de <u><?=htmlspecialchars($app['name'])?></u></h1>
				<div class="connection">
					<?=htmlspecialchars($user['name'])?>
					<img width="50" src="avatar.php">
					❌
					<img width="50" src="<?= $app['icon'] ?? '' ?>" />
					<?=htmlspecialchars($app['name'])?>
				</div>
				<span class="error"><u><?=htmlspecialchars($app['name'])?></u> n'aura plus accès à :</span>
				<ul>
					<li>ton nom d'utilisateur et ton id</li>
					<!--<li>ton adresse e-mail</li>-->
				</ul>
				<input type="submit" value="Déconnecter" class="bad" />
			</form>
			<?php } ?>
		</section>
		<?php if (isset($app)) { ?><div class="custom-background" style="background-image: url('<?=addslashes($app['background'])?>');"></div><?php } ?>
	</body>
</html>
