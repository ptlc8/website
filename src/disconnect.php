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
			<div>
				<h1>Mmh... Cette page a été mal appelée 🤯</h1>
				<a href="javascript:history.back()">🔙 Revenir en arrière</a>
				<a href=".">🏠 Retouner à la page d'accueil </a>
			</div>
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
				<div>
					<h1><u><?=htmlspecialchars($_REQUEST['app'])?></u> déconnecté</h1>
					<p class="helper">Cette application a été déconnecté de ton compte. 😢</p>
				</div>
			<?php } else { ?>
			<form method="post" action="">
				<h1>Déconnecter de <u><?=htmlspecialchars($_REQUEST['app'])?></u></h1>
				<div class="connection">
					<?=htmlspecialchars($user['name'])?>
					<img width="50" src="avatar.php">
					❌
					<img width="50" src="<?= $_REQUEST['icon'] ?? '' ?>" />
					<?=htmlspecialchars($_REQUEST['app'])?>
				</div>
				<span class="error"><u><?=htmlspecialchars($_REQUEST['app'])?></u> n'aura plus accès à :</span>
				<ul>
					<li>ton nom d'utilisateur et ton id</li>
					<li>ton adresse e-mail</li>
				</ul>
				<input type="submit" value="Déconnecter" />
			</form>
			<?php } ?>
		</section>
	</body>
</html>
