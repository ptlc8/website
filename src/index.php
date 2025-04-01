<?php include('api/init.php'); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title><?= htmlspecialchars(get_site_name()) ?> - Projets</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="index.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="favicon.ico" />
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
			<a href="." title="Page de projets">Projets</a>
			<a href="<?= get_protocol() ?>://status.<?= get_host() ?>" title="Page d'état">Statuts</a>
			<a href="account.php" title="Page de compte" class="account">
				Mon compte
				<img src="avatar.php" alt="avatar" width="64" height="64" />
			</a>
		</header>

		<div id="projects" class="deck">
			<?php
			$sitemap = get_sitemap();
			foreach ($sitemap as $subsite) {
				$id = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $subsite->title));
			?>
				<div class="card" id="<?= $id ?>" style="background-color: <?= $subsite->color ?>;">
					<img class="preview" alt="" src="<?= $subsite->preview ?? '' ?>" />
					<div class="head">
						<img src="<?= $subsite->img ?>" width="128" alt="<?= htmlspecialchars($subsite->title) ?>" />
						<h2 class="title"><?= $subsite->title ?></h2>
						<?php if ($subsite->git) { ?>
							<a class="git" href="<?= $subsite->git ?>" title="Dépôt git">
								<img src="assets/git.png" height="32" alt="git" />
							</a>
						<?php } ?>
					</div>
					<div class="detail">
						<?php foreach ($subsite->content as $button) { ?>
							<a href="<?= $button->link ?? '#' ?>" title="<?= htmlspecialchars($button->title) ?>">
								<?= $button->title ?>
							</a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>

		<footer>
			<?= htmlspecialchars(get_site_data()->copyright) ?>
		</footer>
	</body>
</html>
