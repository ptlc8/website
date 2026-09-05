<?php
include('init.php');
include('components.php');

$all = get_sitemap();
$featuredList = preg_replace('/, ([^,]+)$/', ' et $1', implode(', ', array_column(array_slice(get_featured_projects(), 0, 3), 'title')));
$projectsDescription = 'Explore les '.count($all).' jeux, outils et expériences web sur '.SITE_NAME.($featuredList ? ', dont '.$featuredList : '').'.';
function query_value($name, $default) {
	return isset($_GET[$name]) && is_string($_GET[$name]) ? $_GET[$name] : $default;
}
$requestedStatus = trim(query_value('status', 'all'));
$requestedType = trim(strtolower(query_value('type', 'all')));
$requestedSort = query_value('sort', 'selection');

$statusCounts = [];
$typeCounts = [];
foreach ($all as $project) {
	$statusValue = trim((string) ($project->status ?? ''));
	if ($statusValue !== '') $statusCounts[$statusValue] = ($statusCounts[$statusValue] ?? 0) + 1;
	foreach (get_project_types($project) as $typeValue)
		$typeCounts[$typeValue] = ($typeCounts[$typeValue] ?? 0) + 1;
}
ksort($typeCounts);

$status = $requestedStatus === 'all' || isset($statusCounts[$requestedStatus]) ? $requestedStatus : 'all';
$type = $requestedType === 'all' || isset($typeCounts[$requestedType]) ? $requestedType : 'all';
$allowedSorts = ['selection', 'year-desc', 'year-asc', 'status', 'title'];
$sort = in_array($requestedSort, $allowedSorts, true) ? $requestedSort : 'selection';

$projects = array_values(array_filter($all, function($project) use ($status, $type) {
	$statusMatch = $status === 'all' || ($project->status ?? '') === $status;
	$typeMatch = $type === 'all' || in_array($type, get_project_types($project), true);
	return $statusMatch && $typeMatch;
}));

$sorts = [
	'year-desc' => fn($a, $b) => (($b->year ?? 0) <=> ($a->year ?? 0)) ?: strcasecmp($a->title ?? '', $b->title ?? ''),
	'year-asc' => fn($a, $b) => (($a->year ?? 0) <=> ($b->year ?? 0)) ?: strcasecmp($a->title ?? '', $b->title ?? ''),
	'status' => function($a, $b) {
		$order = ['stable' => 0, 'expérimental' => 1, 'archivé' => 2];
		return (($order[$a->status ?? ''] ?? 99) <=> ($order[$b->status ?? ''] ?? 99))
			?: (($b->year ?? 0) <=> ($a->year ?? 0));
	},
	'title' => fn($a, $b) => strcasecmp($a->title ?? '', $b->title ?? ''),
];
if ($sort !== 'selection') usort($projects, $sorts[$sort]);

function filter_url($changes = []) {
	global $status, $type, $sort;
	$params = ['status' => $status, 'type' => $type, 'sort' => $sort];
	$params = array_merge($params, $changes);
	$params = array_filter($params, fn($value) => $value !== 'all' && $value !== 'selection');
	return 'projects.php'.($params ? '?'.http_build_query($params) : '');
}
?>
<!DOCTYPE html>
<html lang="fr">
	<?php render_head(SITE_NAME.' — Projets', '/projects.php', $projectsDescription); ?>
	<body>
		<?php render_header('projects'); ?>
		<main id="contenu" class="projects-page">
			<section class="projects-intro" aria-labelledby="projects-title">
				<p class="eyebrow">Inventaire de l’atelier</p>
				<h1 id="projects-title">Projets, prototypes<br />et curiosités numériques.</h1>
			</section>

			<?php if ($typeCounts || $statusCounts) { ?>
			<section class="catalog-controls" aria-label="Filtrer les projets">
				<?php if ($typeCounts) { ?>
				<div class="filter-row">
					<span class="filter-label">Type</span>
					<nav class="filter-chips" aria-label="Catégories de projets">
						<a href="<?= e(filter_url(['type' => 'all'])) ?>" <?= $type === 'all' ? 'aria-current="page"' : '' ?>>Tous <span><?= count($all) ?></span></a>
						<?php foreach ($typeCounts as $typeName => $count) { ?>
							<a href="<?= e(filter_url(['type' => $typeName])) ?>" <?= $type === $typeName ? 'aria-current="page"' : '' ?>><?= e(ucfirst($typeName)) ?> <span><?= $count ?></span></a>
						<?php } ?>
					</nav>
				</div>
				<?php } ?>
				<div class="filter-secondary">
					<?php if ($statusCounts) { ?>
					<div class="filter-row">
						<span class="filter-label">État</span>
						<nav class="filter-chips" aria-label="État des projets">
							<a href="<?= e(filter_url(['status' => 'all'])) ?>" <?= $status === 'all' ? 'aria-current="page"' : '' ?>>Tous</a>
							<?php foreach ($statusCounts as $statusName => $count) { ?>
								<a href="<?= e(filter_url(['status' => $statusName])) ?>" <?= $status === $statusName ? 'aria-current="page"' : '' ?>><?= e(ucfirst($statusName)) ?> <span><?= $count ?></span></a>
							<?php } ?>
						</nav>
					</div>
					<?php } ?>
					<div class="filter-row">
						<span class="filter-label">Tri</span>
						<nav class="filter-chips" aria-label="Ordre des projets">
							<?php foreach ([
								'selection' => 'Sélection', 'year-desc' => 'Plus récents', 'year-asc' => 'Plus anciens',
								'status' => 'Par état', 'title' => 'Alphabétique'
							] as $sortName => $sortLabel) { ?>
								<a href="<?= e(filter_url(['sort' => $sortName])) ?>" <?= $sort === $sortName ? 'aria-current="page"' : '' ?>><?= e($sortLabel) ?></a>
							<?php } ?>
						</nav>
					</div>
				</div>
			</section>
			<?php } ?>

			<div class="catalog-summary" aria-live="polite">
				<span><strong><?= count($projects) ?></strong> résultat<?= count($projects) > 1 ? 's' : '' ?></span>
				<?php if ($status !== 'all' || $type !== 'all' || $sort !== 'selection') { ?><a href="projects.php">Réinitialiser les filtres</a><?php } ?>
			</div>

			<?php if ($projects) { ?>
				<section class="project-grid" aria-label="Liste des projets">
					<?php foreach ($projects as $project) render_project_card($project); ?>
				</section>
			<?php } else { ?>
				<section class="empty-state"><span aria-hidden="true">≈</span><h2>Aucune île par ici.</h2><p>Essayez une autre combinaison de filtres.</p><a href="projects.php">Voir tous les projets</a></section>
			<?php } ?>
		</main>
		<?php render_footer(); ?>
	</body>
</html>
