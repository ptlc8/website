<?php
include('init.php');
include('components.php');

$projects = get_sitemap();
$featured = array_slice(get_featured_projects(), 0, 3);
?>
<!DOCTYPE html>
<html lang="fr">
	<?php render_head(SITE_NAME, '/', SITE_NAME." est mon site personnel : j’y rassemble des jeux, des outils et des expériences web que je développe et auto-héberge."); ?>
	<body>
		<?php if (date('n') == 4 && date('j') == 1) { ?><div id="april-fool"></div><?php } ?>
		<?php render_header('home'); ?>

		<main id="contenu">
			<section class="hero" aria-labelledby="hero-title">
				<div class="hero-copy">
					<p class="eyebrow">Atelier numérique personnel</p>
					<h1 id="hero-title">Je développe des jeux, des outils et des expériences <em>pour le web.</em></h1>
					<p class="hero-intro">Bienvenue dans mon archipel d’internet : tu peux y essayer mes projets, consulter leur code et découvrir l’infrastructure qui les héberge.</p>
					<div class="hero-actions">
						<a class="button button-primary" href="projects.php">Explorer les projets <span aria-hidden="true">→</span></a>
						<a class="button button-secondary" href="#hebergement">Voir les coulisses <span aria-hidden="true">↓</span></a>
					</div>
				</div>
				<div class="archipelago" aria-hidden="true">
					<img src="assets/archipelago.svg" alt="" />
					<span class="map-note map-note--one">jeux</span>
					<span class="map-note map-note--two">outils</span>
					<span class="map-note map-note--three">expériences</span>
				</div>
			</section>

			<?php if ($featured) { ?>
			<section class="featured-section" aria-labelledby="featured-title">
				<div class="section-heading">
					<div><p class="eyebrow">Quelques escales</p><h2 id="featured-title">Projets à explorer</h2></div>
					<a href="projects.php">Voir les <?= count($projects) ?> projets <span aria-hidden="true">→</span></a>
				</div>
				<div class="featured-grid">
					<?php foreach ($featured as $project) render_project_card($project); ?>
				</div>
			</section>
			<?php } ?>

			<section class="hosting-note" id="hebergement" aria-labelledby="hosting-title">
				<div class="note-pin" aria-hidden="true"></div>
				<p class="eyebrow">Note technique nº 01</p>
				<h2 id="hosting-title">Hébergé à la maison</h2>
				<p>L’ensemble de <?= e(SITE_NAME) ?> est auto-hébergé sur mon infrastructure personnelle. C’est une façon de garder le contrôle, d’apprendre en pratiquant et de construire un web plus indépendant.</p>
				<details>
					<summary>Ouvrir le carnet technique</summary>
					<p>L’ensemble est publié derrière un reverse proxy et un tunnel sécurisé, puis isolé dans des conteneurs pour rester portable et reproductible.</p>
					<ul class="technical-list">
						<li><strong>Conteneurs</strong><span>Docker et Docker Compose</span></li>
						<li><strong>Routage</strong><span>Reverse proxy maison avec <a href="https://github.com/ptlc8/apache-docker-proxy" target="_blank" rel="noopener noreferrer">apache-docker-proxy</a></span></li>
						<li><strong>Sécurité</strong><span>SSL/TLS avec Let’s Encrypt ou Cloudflare</span></li>
						<li><strong>Déploiement</strong><span>Intégration et livraison automatisées avec Jenkins</span></li>
						<li><strong>Code</strong><span>GitHub et GitLab, avec centralisation et mirroring des dépôts</span></li>
						<li><strong>Sauvegardes</strong><span>Tâches cron, dumps et réplication Nextcloud</span></li>
					</ul>
				</details>
			</section>
		</main>

		<?php render_footer(); ?>
	</body>
</html>
