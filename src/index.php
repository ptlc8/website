<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<?php $title = 'Accueil'; include('seo.php'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="index.css" />
		<script src="index.js" async defer></script>
	</head>
	<body>
		<?php if (date('n') == 4 && date('j') == 1) echo '<div id="april-fool"></div>'; ?>
		<header>
			<h1><?= WEBSITE_NAME ?></h1>
			<a href=".">Projets</a>
			<a href="#">Serveur</a>
			<a href="account.php" class="account">
				Mon compte
				<img src="avatar.php" alt="avatar" width="64" height="64" />
			</a>
		</header>
		<div id="projects" class="deck"></div>
	</body>
</html>
