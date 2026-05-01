<?php
include('init.php');

$status = $_GET['status'] ?? 'all';
$type = $_GET['type'] ?? 'all';
$sort = $_GET['sort'] ?? 'status';

$all = get_sitemap();
$subsites = array_filter($all, function($subsite) use ($status, $type) {
	$statusMatch = $status === 'all' || ($subsite->status ?? '') === $status;
	$types = $subsite->type ?? [];
	$types = is_array($types) ? $types : [$types];
	$typeMatch = $type === 'all' || in_array($type, $types);
	return $statusMatch && $typeMatch;
});

// Trier
function hue($hex) {
	return atan2(sqrt(3) * (hexdec(substr($hex,3,2)) - hexdec(substr($hex,5,2))), 2 * hexdec(substr($hex,1,2)) - hexdec(substr($hex,3,2)) - hexdec(substr($hex,5,2)));
}

$sorts = ['year-desc' => fn($a,$b) => ($b->year ?? 0) - ($a->year ?? 0), 
          'year-asc' => fn($a,$b) => ($a->year ?? 0) - ($b->year ?? 0), 
          'status' => fn($a,$b) => ($b->status ?? '') <=> ($a->status ?? ''), 
          'color' => fn($a,$b) => hue($a->color ?? '#000') <=> hue($b->color ?? '#000')];
usort($subsites, $sorts[$sort] ?? $sorts['status']);

// Compteurs
$counts = ['status' => ['all' => count($all)], 'type' => ['all' => count($all)]];
foreach ($all as $subsite) {
	$s = $subsite->status ?? '';
	$counts['status'][$s] = ($counts['status'][$s] ?? 0) + 1;

	$types = $subsite->type ?? [];
	$types = is_array($types) ? $types : [$types];
	foreach ($types as $t) {
		$counts['type'][$t] = ($counts['type'][$t] ?? 0) + 1;
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title><?= htmlspecialchars(get_site_name()) ?> - Projets</title>
		<link rel="stylesheet" href="style.css" />
		<style>
		body {
			animation: animated-bg 6s ease-in-out infinite alternate <?= -(fmod(microtime(true), 12)) ?>s, 
			           coloring-bg 3s ease-in-out infinite alternate <?= -(fmod(microtime(true), 6)) ?>s;
		}
		</style>
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
			<h1>
				<a href="."><?= htmlspecialchars(get_site_name()) ?></a>
			</h1>
			<nav>
				<a class="button" href="." title="Page de projets">Accueil</a>
				<a class="button" href="projects.php" title="Page de projets">Projets</a>
				<a class="button" href="<?= get_protocol() ?>://status.<?= get_host() ?>" title="Page d'état">Statuts</a>
				<a class="button" href="<?= get_protocol() ?>://view.<?= get_host() ?>" title="Graphe de l'infrastructure">Graphe</a>
			</nav>
			<?php if ($auth_url = get_auth_url()) { ?>
				<a class="account button" href="<?= $auth_url ?>" title="Page de compte">
					Mon compte
					<img src="<?= $auth_url ?>/avatar.php" alt="avatar" width="64" height="64" />
				</a>
			<?php } ?>
		</header>

		<main>
			<form class="filters-container" method="get">
				<div class="filter-group">
					<label for="status">Statut :</label>
					<select name="status" id="status" onchange="this.form.submit()">
						<?php foreach ($counts['status'] as $s => $count) { ?>
							<option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>>
								<?= $s === 'all' ? 'Tous' : ucfirst($s) ?> (<?= $count ?>)
							</option>
						<?php } ?>
					</select>
				</div>
				
				<div class="filter-group">
					<label for="type">Type :</label>
					<select name="type" id="type" onchange="this.form.submit()">
						<?php foreach ($counts['type'] as $t => $count) { ?>
							<option value="<?= $t ?>" <?= $type === $t ? 'selected' : '' ?>>
								<?= $t === 'all' ? 'Tous' : ucfirst($t) ?> (<?= $count ?>)
							</option>
						<?php } ?>
					</select>
				</div>
				
				<div class="filter-group">
					<label for="sort">Tri :</label>
					<select name="sort" id="sort" onchange="this.form.submit()">
						<option value="year-desc" <?= $sort === 'year-desc' ? 'selected' : '' ?>>Plus récents</option>
						<option value="year-asc" <?= $sort === 'year-asc' ? 'selected' : '' ?>>Plus anciens</option>
						<option value="status" <?= $sort === 'status' ? 'selected' : '' ?>>Par statut</option>
						<option value="color" <?= $sort === 'color' ? 'selected' : '' ?>>Par couleur</option>
					</select>
				</div>
				
				<?php if ($status !== 'all' || $type !== 'all' || $sort !== 'status') { ?>
					<a href="projects.php" class="button">Réinitialiser</a>
				<?php } ?>
			</form>

			<div class="deck">
				<?php foreach ($subsites as $subsite) { ?>
					<div class="card" id="<?= slugify($subsite->title) ?>" style="background-color:<?= $subsite->color ?>;" onclick="location.href='<?= $subsite->content[0]->link ?? '#' ?>'">
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
							<div class="meta-info">
								<?php if ($subsite->status ?? false) { ?>
									<span class="badge" style="--badge-color: <?= textToRgb($subsite->status) ?>;" title="Statut du projet">
										<?= htmlspecialchars(ucfirst($subsite->status)) ?>
									</span>
								<?php } ?>
								<?php 
								$types = $subsite->type ?? [];
								$types = is_array($types) ? $types : [$types];
								foreach ($types as $type) { ?>
									<span class="badge badge-type" title="Type de projet">
										<?= htmlspecialchars(ucfirst($type)) ?>
									</span>
								<?php } ?>
								<?php if ($subsite->year ?? false) { ?>
									<span class="badge badge-year" title="Année de création">
										<?= htmlspecialchars($subsite->year) ?>
									</span>
								<?php } ?>
							</div>
							<?php if ($subsite->description ?? false) { ?>
								<p><?= htmlspecialchars($subsite->description) ?></p>
							<?php } ?>
							<div class="buttons">
								<?php foreach ($subsite->content ?? [] as $button) { ?>
									<a class="button" href="<?= $button->link ?? '#' ?>" title="<?= htmlspecialchars($button->title ?? '') ?>" onclick="event.stopPropagation()">
										<?= htmlspecialchars($button->title ?? '') ?>
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</main>

		<footer>
			<?= htmlspecialchars(get_site_data()->copyright) ?>
			- Fait maison avec ❤️
		</footer>
	</body>
</html>
