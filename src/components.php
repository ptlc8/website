<?php

function e($value) {
	return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function safe_url($url) {
	$url = trim((string) $url);
	if ($url === '' || $url === '#')
		return null;

	$scheme = parse_url($url, PHP_URL_SCHEME);
	if ($scheme !== null && !in_array(strtolower($scheme), ['http', 'https'], true))
		return null;

	if ($scheme === null && (str_starts_with($url, '//') || preg_match('/[\x00-\x1F\x7F]/', $url)))
		return null;

	return $url;
}

function canonical_url($path = '/') {
	return get_protocol().'://'.get_host().'/'.ltrim($path, '/');
}

function project_links($project) {
	$links = [];
	foreach ($project->content ?? [] as $link) {
		$url = safe_url($link->link ?? '');
		if ($url)
			$links[] = ['url' => $url, 'title' => trim((string) ($link->title ?? 'Ouvrir')) ?: 'Ouvrir'];
	}
	return $links;
}

function render_head($title, $path, $description) {
	$canonical = canonical_url($path);
	?>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= e($title) ?></title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" href="favicon.ico" />
		<meta name="description" content="<?= e($description) ?>" />
		<meta name="robots" content="index, follow" />
		<link rel="canonical" href="<?= e($canonical) ?>" />
		<meta property="og:url" content="<?= e($canonical) ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= e($title) ?>" />
		<meta property="og:description" content="<?= e($description) ?>" />
		<meta property="og:image" content="<?= e(canonical_url('/favicon.ico')) ?>" />
		<meta name="theme-color" content="#f3eedb" media="(prefers-color-scheme: light)" />
		<meta name="theme-color" content="#17151d" media="(prefers-color-scheme: dark)" />
	</head>
	<?php
}

function render_header($active = 'home') {
	[$name, $tld] = explode('.', SITE_NAME, 2);
	?>
	<a class="skip-link" href="#contenu">Aller au contenu</a>
	<header class="site-header">
		<a class="wordmark" href="./" aria-label="<?= e(SITE_NAME) ?> — accueil">
			<span><?= e($name) ?></span><b>.<?= e($tld) ?></b>
		</a>
		<nav aria-label="Navigation principale">
			<a href="./" <?= $active === 'home' ? 'aria-current="page"' : '' ?>>Accueil</a>
			<a href="projects.php" <?= $active === 'projects' ? 'aria-current="page"' : '' ?>>Projets</a>
			<a href="https://github.com/ptlc8" target="_blank" rel="noopener noreferrer">GitHub <span aria-hidden="true">↗</span></a>
		</nav>
	</header>
	<?php
}

function render_project_card($project) {
	$title = trim((string) ($project->title ?? 'Projet sans titre'));
	$links = project_links($project);
	$primary = $links[0] ?? null;
	$preview = safe_url($project->preview ?? '');
	$git = safe_url($project->git ?? '');
	$types = get_project_types($project);
	$hasMeta = !empty($project->year) || !empty($project->status);
	?>
	<article class="project-card" style="--accent: <?= e($project->color ?? '#6f45c5') ?>">
		<div class="project-media">
			<?php if ($preview) { ?>
				<img src="<?= e($preview) ?>" alt="" loading="lazy" decoding="async" />
			<?php } else { ?>
				<div class="project-placeholder" aria-hidden="true"><span>✦</span><span>≈</span><span>●</span></div>
			<?php } ?>
		</div>
		<div class="project-content">
			<?php if ($hasMeta) { ?>
				<div class="project-meta">
					<?php if (!empty($project->year)) { ?><span><?= e($project->year) ?></span><?php } ?>
					<?php if (!empty($project->status)) { ?><span><?= e(ucfirst($project->status)) ?></span><?php } ?>
				</div>
			<?php } ?>
			<h3>
				<?php if ($primary) { ?><a href="<?= e($primary['url']) ?>"><?= e($title) ?></a><?php } else { echo e($title); } ?>
			</h3>
			<?php if (!empty($project->description)) { ?><p><?= e($project->description) ?></p><?php } ?>
			<?php if ($types) { ?>
				<ul class="project-tags" aria-label="Catégories">
					<?php foreach ($types as $type) { ?><li><?= e($type) ?></li><?php } ?>
				</ul>
			<?php } ?>
			<?php if ($links || $git) { ?>
				<div class="project-actions">
					<?php foreach ($links as $index => $link) { ?>
						<a class="<?= $index === 0 ? 'action-primary' : 'action-secondary' ?>" href="<?= e($link['url']) ?>"><?= e($link['title']) ?><span aria-hidden="true"> →</span></a>
					<?php } ?>
					<?php if ($git) { ?><a class="action-code" href="<?= e($git) ?>" target="_blank" rel="noopener noreferrer">Code <span aria-hidden="true">↗</span></a><?php } ?>
				</div>
			<?php } ?>
		</div>
	</article>
	<?php
}

function render_footer() {
	$auth = safe_url(get_auth_url());
	?>
	<footer class="site-footer">
		<div>
			<strong><?= e(SITE_NAME) ?></strong>
			<p>Jeux, outils et expériences web faits maison.</p>
		</div>
		<nav aria-label="Liens d’infrastructure">
			<span>Infrastructure</span>
			<a href="<?= e(get_protocol().'://status.'.get_host()) ?>">Statuts</a>
			<a href="<?= e(get_protocol().'://view.'.get_host()) ?>">Graphe</a>
			<?php if ($auth) { ?><a href="<?= e($auth) ?>">Mon compte</a><?php } ?>
		</nav>
		<small>© <?= date('Y').' '.e(SITE_AUTHOR) ?> — Tous droits réservés<br />Fait maison avec soin.</small>
	</footer>
	<?php
}
