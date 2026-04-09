<?php include('init.php'); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title><?= htmlspecialchars(get_site_name()) ?> - Projets</title>
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

		<main id="projects" class="deck">
			<?php
			$sitemap = get_sitemap();
			foreach ($sitemap as $subsite) {
				$id = slugify($subsite->title);
				$firstLink = !empty($subsite->content) ? ($subsite->content[0]->link ?? '#') : '#';
			?>
				<div class="card" id="<?= $id ?>" style="background-color: <?= $subsite->color ?>;" onclick="location.href='<?= $firstLink ?>'">
					<img class="preview" alt="" src="<?= htmlspecialchars($subsite->preview ?? '') ?>" />
					<div class="head">
						<img src="<?= htmlspecialchars($subsite->img) ?>" width="128" alt="<?= htmlspecialchars($subsite->title) ?>" />
						<h2 class="title"><?= htmlspecialchars($subsite->title) ?></h2>
						<?php if ($subsite->git ?? false) { ?>
							<a class="button git" href="<?= htmlspecialchars($subsite->git) ?>" target="_blank" title="Dépôt git" onclick="event.stopPropagation()">
								<img src="assets/git.png" height="32" alt="git" />
							</a>
						<?php } ?>
					</div>
					<div class="body">
						<?php if ($subsite->description ?? false) { ?>
							<p><?= htmlspecialchars($subsite->description) ?></p>
						<?php } ?>
						<div class="buttons">
							<?php foreach ($subsite->content as $button) { ?>
								<a class="button" href="<?= $button->link ?? '#' ?>" title="<?= htmlspecialchars($button->title ?? '') ?>" onclick="event.stopPropagation()">
									<?= htmlspecialchars($button->title ?? '') ?>
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</main>

		<footer>
			<?= htmlspecialchars(get_site_data()->copyright) ?>
			- Fait maison avec ❤️
		</footer>
	</body>
</html>
