<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>Ambi.dev - Partage de projets</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="index.css" />
		<script src="index.js" async defer></script>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="favicon.ico" />
		<meta name="language" content="fr-FR" />
		<meta name="sitename" content="ambi.dev" />
		<meta name="keywords" content="agenda,partagé,partage,poké,profs,poképrofs,calculateur,moyenne,eisti,cy,tech,agendas,share,shared,teachers,cards,cartes,prof,jmas,abo,abonosaure,smash,meme,histoire,hub,uwu,listes" />
		<meta name="description" content="Sur ce site, vous trouverez des agendas partageables, le jeu de carte à jouer Poképrofs et un calculateur de moyenne." />
		<meta name="robots" content="index, follow" />
		<meta name="copyright" content="Ambi / PTLC - Tous droits réservés" />
		<meta name="author" content="Ambi alias PTLC" />
		<link rel="canonical" href="https://ambi.dev/" />
	</head>
	<body>
		<?php if (date('n') == 4 && date('j') == 1) echo '<div id="april-fool"></div>'; ?>
		<header>
			<h1>Ambi.dev</h1>
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
