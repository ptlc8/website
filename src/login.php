<?php
$fail = false;
$close = false;
include("api/init.php");
if (isset($_REQUEST['username'], $_POST['password'])) {
	$user = login($_REQUEST['username'], $_POST['password']);
	if ($user !== null) {
		if (isset($_REQUEST['go']))
			exit(header('Location: '.$_REQUEST['go']));
		if (isset($_REQUEST['closeafter']))
			$close = true;
		else
			exit(header('Location: .'));
	} else {
		$fail = true;
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Connexion | <?= get_site_name() ?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<section class="floating container">
			<form method="post" action="">
				<h1>Connexion</h1>
				<label for="username">Nom d'utilisateur</label>
				<input id="username" name="username" type="text" placeholder="Nom d'utilisateur" <?php echo(isset($_REQUEST['username']) ? 'value="'.$_REQUEST['username'].'"' : '') ?> autocomplete="name" autofocus />
				<label for="password">Mot de passe</label>
				<input id="password" name="password" type="password" placeholder="Mot de passe" autocomplete="current-password" />
				<input type="submit" value="Se connecter" class="good" />
				<?php
				if ($fail) { ?>
					<p class="error">Nom d'utilisateur ou mot de passe invalide. 😱</p>
				<?php } else if ($close) { ?>
					<p class="helper">Vous êtes connecté(e), vous pouvez fermer cet onglet. 🎉</p>
					<script>window.close();</script>
				<?php } ?>
				<a class="large" href="register.php<?php echo isset($_REQUEST['go']) ? '?go='.urlencode($_REQUEST['go']) : (isset($_REQUEST['closeafter']) ? '?closeafter' : '?'); ?>">🎉 Je me connecte pour la première fois</a>
				<a class="large" href="forgotten-password.php">😓 J'ai oublié mon mot de passe</a>
			</form>
		</section>
	</body>
</html>
