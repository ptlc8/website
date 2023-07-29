<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Suppression | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<section class="floating container">
			<?php
			include("api/init.php");
			$user = login();
			if ($user == null) {
				header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI']));
			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				sendRequest("DELETE FROM `USERS` WHERE `id` = '", $user['id'], "';");
			?>
			<div>
				<h1>Compte supprimé</h1>
				<p class="helper">Ton compte a bien été supprimé. 😫</p>
				<a href=".">🏠 Retouner à la page d'accueil</a>
			</div>
			<?php } else { ?>
			<form method="post" action="">
				<h1>Supprimer ton compte</h1>
				<span class="error">⚠ Cette action est irréversible</span>
				<input type="submit" value="Supprimer mon compte" class="bad" />
			</form>
			<?php } ?>
		</section>
	</body>
</html>