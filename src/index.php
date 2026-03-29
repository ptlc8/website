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
			<section class="hero">
				<h2>Bienvenue dans mon archipel d'internet</h2>
				<p>Un espace personnel où je partage mes projets et expérimentations :</p>
				<ul>
					<li data-icon="🎮">Des <a href="projects.php">projets</a> variés (jeux, outils, etc.)</li>
					<li data-icon="🧪">Un terrain de jeu pour tester de nouvelles idées</li>
					<li data-icon="🏠">Une <a href="#hebergement">infrastructure maison</a> pour l'indépendance numérique</li>
				</ul>
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
					</div>
				<?php } else { ?>
					<p>Aucun projet mis en avant pour le moment.</p>
				<?php } ?>
				<div style="text-align: center; margin-top: 1em;">
					<a class="button" href="projects.php">Découvrir tous les projets →</a>
				</div>
			</section>

			<section id="hebergement">
				<h2>Hébergement maison</h2>
				<p>
					Ce site tourne sur un serveur personnel hébergé chez moi. 
					Pourquoi ? Pour garder le contrôle sur mes données, apprendre en pratiquant, 
					et construire une infrastructure résiliente.
				</p>
				<details>
					<summary>🤓 Détails techniques</summary>
					<p>
						Rendu accessible via un système de proxy inversé et un tunnel sécurisé. 
						L'ensemble tourne dans des conteneurs Docker pour garantir isolation et portabilité.
					</p>
					<ul>
						<li data-icon="🐋">Docker & Docker Compose</li>
						<li data-icon="🔀">Reverse proxy (<a href="https://github.com/ptlc8/apache-docker-proxy" target="_blank">apache-docker-proxy</a>)</li>
						<li data-icon="🔒">SSL/TLS (Let's Encrypt ou Cloudflare)</li>
						<li data-icon="⚙️">Jenkins (déploiement automatisé)</li>
						<li data-icon="📦">Git & GitHub (versioning et centralisation du code)</li>
					</ul>
				</details>
			</section>
		</main>

		<footer>
			<?= htmlspecialchars(get_site_data()->copyright) ?>
			- Fait maison avec ❤️
			<span id="confetti-trigger" style="cursor: pointer; margin-left: 0.5em;" title="Cliquer pour faire la fête !">🎉</span>
		</footer>

		<script>
		// Easter eggs
		(function() {
			// Fonction pour créer des confettis
			function createConfetti() {
				const confetti = document.createElement('div');
				confetti.className = 'confetti';
				confetti.style.left = Math.random() * 100 + '%';
				confetti.style.top = '-10px';
				const colors = ['#ff0', '#0f0', '#0ff', '#f0f', '#f00', '#ff8c00', '#00ff00', '#ff1493'];
				confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
				confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
				document.body.appendChild(confetti);
				setTimeout(() => confetti.remove(), 4000);
			}

			// Clic sur emoji 🎉 dans le footer
			const confettiTrigger = document.getElementById('confetti-trigger');
			if (confettiTrigger) {
				confettiTrigger.addEventListener('click', function() {
					// Créer plein de confettis pendant 3 secondes
					let count = 0;
					const interval = setInterval(() => {
						createConfetti();
						count++;
						if (count >= 15) { // 15 confettis au total
							clearInterval(interval);
						}
					}, 200);
					console.log('🎉 Confettis lancés !');
				});
			}

			// Triple-click sur le titre (désactiver la sélection)
			const title = document.querySelector('header h1');
			let clickCount = 0;
			let clickTimer = null;

			title.style.userSelect = 'none';
			title.style.cursor = 'pointer';

			title.addEventListener('click', function(e) {
				e.preventDefault();
				clickCount++;
				if (clickTimer) clearTimeout(clickTimer);
				
				if (clickCount === 3) {
					this.classList.toggle('secret-active');
					clickCount = 0;
					console.log('🌈 Mode arc-en-ciel activé !');
				} else {
					clickTimer = setTimeout(() => {
						clickCount = 0;
					}, 500);
				}
			});

			// Bonus: double-clic sur le footer
			const footer = document.querySelector('footer');
			footer.addEventListener('dblclick', function() {
				const hearts = ['❤️', '💚', '💙', '💜', '🧡', '💛'];
				const randomHeart = hearts[Math.floor(Math.random() * hearts.length)];
				this.innerHTML = this.innerHTML.replace('❤️', randomHeart);
			});
		})();
		</script>
	</body>
</html>
