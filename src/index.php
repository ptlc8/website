<?php include('api/init.php'); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title><?= get_site_name() ?> - Partage de projets</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="index.css" />
		<script src="index.js" async defer></script>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="favicon.ico" />
		<meta name="language" content="fr-FR" />
		<meta name="sitename" content="<?= get_site_name() ?>" />
		<meta name="keywords" content="<?= get_site_data()->keywords ?>" />
		<meta name="description" content="<?= get_site_data()->description ?>" />
		<meta name="robots" content="index, follow" />
		<meta name="copyright" content="<?= get_site_data()->copyright ?>" />
		<meta name="author" content="<?= get_site_data()->author ?>" />
		<link rel="canonical" href="https://<?= get_host() ?>" />
	</head>
	<body>
		<?php if (date('n') == 4 && date('j') == 1) echo '<div id="april-fool"></div>'; ?>
		<header>
			<h1><?= get_site_name() ?></h1>
			<a href=".">Projets</a>
			<a href="<?= get_protocol() ?>://status.<?= get_host() ?>">Status</a>
			<a href="account.php" class="account">
				Mon compte
				<img src="avatar.php" alt="avatar" width="64" height="64" />
			</a>
		</header>
		<div id="projects" class="deck"></div>
		<footer>
			<?= get_site_data()->copyright ?>
		</footer>
	</body>
</html>
