<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>Ambi.dev - ex CY Hub - ex agnd - Partage de projets</title>
		<link rel="stylesheet" href="index.css" />
		<!--<meta name="viewport" content="width=device-width, initial-scale=1" />-->
		<link rel="shortcut icon" href="favicon.ico" />
		<meta name="language" content="fr-FR" />
		<meta name="sitename" content="agnd.fr" />
		<meta name="keywords" content="agenda,partagé,partage,poké,profs,poképrofs,calculateur,moyenne,eisti,cy,tech,agendas,share,shared,teachers,cards,cartes,prof,agnd,jmas,jmax,abo,abonosaure,smash,meme,histoire" />
		<meta name="description" content="Sur ce site, vous trouverez des agendas partageables, le jeu de carte à jouer Poképrofs et un calculateur de moyenne." />
		<meta name="robots" content="index, follow" />
		<meta name="copyright" content="PTLC - Tous droits réservés" />
		<meta name="author" content="PTLC" />
	</head>
	<body>
        <?php if (date('n') == 4 && date('j') == 1) echo '<div id="april-fool"></div>'; ?>
		<div class="triptych">
			<div class="tab blue">
			    <img src="/cards/assets/back.png" />
			    <span class="title">Poképrof</span>
			    <div class="content">
    			    <a href="/cards" class="inner-link">Jouer</a>
    			    <a href="/cards/gallery.php" class="inner-link">Gallerie de cartes</a>
    			    <a href="/cards/create.php" class="inner-link">Créer une carte</a>
    			</div>
			</div>
			<!--<a class="tab yellow" href="/agenda">
				<img src="/agenda/favicon.ico" />
				<span class="title">Agenda partagé</span>
			</a>-->
			<div class="tab red">
			    <img src="/notes/favicon.ico" />
			    <span class="title">Trucs utiles</span>
			    <div class="content">
    			    <a href="/notes" class="inner-link">Calculateur de moyenne</a>
    			    <a href="/wheel" class="inner-link">Roue des choix</a>
    			    <a href="/agenda" class="inner-link">Agenda partagé</a>
    			</div>
			</div>
			<div class="tab green">
			    <img src="/sm/assets/glob-lilglob.png" />
			    <span class="title">Smash Meme</span>
			    <div class="content">
    			    <a href="/sm/play" class="inner-link">Tester seul</a>
    			    <a href="/sm/online" class="inner-link">En multijoueur</a>
    			    <a href="/sm/create" class="inner-link">Creér un personnage</a>
    			</div>
			</div>
			<div class="tab yellow">
				<img src="/pcv/assets/player.png" />
				<span class="title">Trucs cools</span>
			    <div class="content">
    			    <a href="/story/create.html" class="inner-link">Créer une "histoire"</a>
    			    <a href="/pcv" class="inner-link">Jojo racing crack</a>
    			    <a href="/dixit" class="inner-link">Dixit</a>
    			</div>
			</div>
			<div class="tab pink">
			    <!--<a class="outer-link" href="story/?script=memes"></a>-->
			    <img src="/ig/random.png">
			    <span class="title">Le bazar...</span>
			    <div class="content">
    			    <a href="/ig" class="inner-link">Images</a>
    			    <a href="/kart" class="inner-link">Voitures claquées</a>
    			    <a href="/maeva" class="inner-link">Maëva</a>
    			</div>
			</div>
		</div>
	</body>
</html>
