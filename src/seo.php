<?php
define('WEBSITE_NAME', 'Ambi.dev');
define('WEBSITE_DESCRIPTION', 'Sur ce site, vous trouverez des agendas partageables, le jeu de carte à jouer Poképrofs et un calculateur de moyenne.');
define('WEBSITE_KEYWORDS', 'agenda,partagé,partage,poké,profs,poképrofs,calculateur,moyenne,eisti,cy,tech,agendas,share,shared,teachers,cards,cartes,prof,jmas,abo,abonosaure,smash,meme,histoire,hub,uwu,listes');
define('WEBSITE_AUTHOR', 'Ambi alias PTLC');
define('WEBSITE_COPYRIGHT', 'Ambi / PTLC - Tous droits réservés');
define('WEBSITE_LANGUAGE', 'fr-FR');
define('WEBSITE_ROBOTS', 'index, follow');
define('WEBSITE_CANONICAL', ($_SERVER['SERVER_HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
?><title><?= $title ? ($title . ' | ') : '' ?><?= WEBSITE_NAME ?></title>
        <link rel="shortcut icon" href="favicon.ico" />
        <meta name="language" content="<?= WEBSITE_LANGUAGE ?>" />
		<meta name="sitename" content="<?= WEBSITE_NAME ?>" />
		<meta name="keywords" content="<?= WEBSITE_KEYWORDS ?>" />
		<meta name="description" content="<?= WEBSITE_DESCRIPTION ?>" />
		<meta name="robots" content="<?= WEBSITE_ROBOTS ?>" />
		<meta name="copyright" content="<?= WEBSITE_COPYRIGHT ?>" />
		<meta name="author" content="<?= WEBSITE_AUTHOR ?>" />
		<link rel="canonical" href="<?= WEBSITE_CANONICAL ?>" />
