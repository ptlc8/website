<?php include('init.php'); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title><?= htmlspecialchars(get_site_name()) ?></title>
		<link rel="stylesheet" href="style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="favicon.ico" />
		<meta name="language" content="fr" />
		<meta name="sitename" content="<?= htmlspecialchars(get_site_name()) ?>" />
		<meta name="keywords" content="<?= htmlspecialchars(get_site_data()->keywords) ?>" />
		<meta name="description" content="<?= htmlspecialchars(get_site_data()->description) ?>" />
		<meta name="robots" content="index, follow" />
		<meta name="copyright" content="<?= htmlspecialchars(get_site_data()->copyright) ?>" />
		<meta name="author" content="<?= htmlspecialchars(get_site_data()->author) ?>" />
		<link rel="canonical" href="<?= get_protocol() ?>://<?= get_host() ?>" />
		<meta property="og:url" content="<?= get_protocol() ?>://<?= get_host() ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= htmlspecialchars(get_site_name()) ?>" />
		<meta property="og:description" content="<?= htmlspecialchars(get_site_data()->description) ?>" />
		<meta property="og:image" content="<?= get_protocol() ?>://<?= get_host() ?>/favicon.ico" />
	</head>
	<body>
		<?php if (date('n') == 4 && date('j') == 1) { ?>
			<div id="april-fool"></div>
		<?php } ?>

		<header>
			<h1><?= htmlspecialchars(get_site_name()) ?></h1>
			<nav>
				<a class="button" href="." title="Page de projets">Accueil</a>
				<a class="button" href="projects.php" title="Page de projets">Projets</a>
				<a class="button" href="<?= get_protocol() ?>://status.<?= get_host() ?>" title="Page d'état">Statuts</a>
			</nav>
			<?php if ($auth_url = get_auth_url()) { ?>
				<a class="account button" href="<?= $auth_url ?>" title="Page de compte">
					Mon compte
					<img src="<?= $auth_url ?>/avatar.php" alt="avatar" width="64" height="64" />
				</a>
			<?php } ?>
		</header>

		<main>
			<section>
				<h2>Bienvenue dans mon homelab !</h2>
				<p>Celui-ci a pour but de :</p>
				<ul>
					<li>Présenter mes <a href="projects.php">projets</a> divers et variés</li>
					<li>Me servir de laboratoire informatique</li>
					<li>Atteindre une certaine indépendance numérique</li>
				</ul>
			</section>
			<section>
				<p>Ce site web est entièrement hébergé localement</p>
				<p>
					Ce site est hébergé sur un serveur personnel à la maison, rendu accessible 
					via un système de proxy inversé et un tunnel sécurisé. L'ensemble de 
					l'infrastructure tourne dans des conteneurs Docker pour garantir isolation 
					et portabilité.
				</p>
				<details>
					<summary>Détails techniques</summary>
					<ul>
						<li>Docker & Docker Compose</li>
						<li>Apache (serveur web)</li>
						<li>Reverse proxy (apache-docker-proxy)</li>
						<li>SSL/TLS (Let's Encrypt ou Cloudflare)</li>
						<li>Jenkins (déploiement automatisé)</li>
						<li>Git & GitHub (versioning et centralisation du code)</li>
					</ul>
				</details>
			</section>
			<section>
				<h2>Projets mis en avant</h2>
				<?php 
				$featured = get_featured_projects();
				if (count($featured) > 0) {
				?>
					<div class="deck">
						<?php foreach ($featured as $subsite) {
							$id = slugify($subsite->title);
						?>
							<div class="card" id="<?= $id ?>" style="background-color: <?= $subsite->color ?>;" onclick="location.href = '#<?= $id ?>'">
								<img class="preview" alt="" src="<?= htmlspecialchars($subsite->preview ?? '') ?>" />
								<div class="head">
									<img src="<?= htmlspecialchars($subsite->img) ?>" width="128" alt="<?= htmlspecialchars($subsite->title) ?>" />
									<h2 class="title"><?= htmlspecialchars($subsite->title) ?></h2>
									<?php if (!empty($subsite->git)) { ?>
										<a class="button git" href="<?= htmlspecialchars($subsite->git) ?>" target="_blank" title="Dépôt git">
											<img src="assets/git.png" height="32" alt="git" />
										</a>
									<?php } ?>
								</div>
								<div class="body">
									<?php if (!empty($subsite->description)) { ?>
										<p><?= htmlspecialchars($subsite->description) ?></p>
									<?php } ?>
									<div class="buttons">
										<?php foreach ($subsite->content as $button) { ?>
											<a class="button" href="<?= $button->link ?? '#' ?>" title="<?= htmlspecialchars($button->title) ?>">
												<?= htmlspecialchars($button->title) ?>
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } else { ?>
					<p>Aucun projet mis en avant pour le moment.</p>
				<?php } ?>
				<div style="text-align: center; margin-top: 1em;">
					<a class="button" href="projects.php">Voir tous les projets →</a>
				</div>
			</section>
		</main>

		<footer>
			<?= htmlspecialchars(get_site_data()->copyright) ?>
		</footer>
	</body>
</html>
