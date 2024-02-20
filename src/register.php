<?php
$website = $_SERVER['HTTP_HOST'];
$error = null;
$close = false;
include('api/init.php');

if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['password2'], $_REQUEST['firstname'], $_REQUEST['lastname'])) {
	// teste si le nom d'utilisateur est déjà pris
	if (request_database("SELECT * FROM USERS WHERE `name` = '", $_REQUEST['username'], "'")->num_rows !== 0)
		$error = 'Ce nom d\'utilisateur est déjà utilisé 😦';
	// teste si l'adresse e-mail est déjà prise
	else if (request_database("SELECT * FROM USERS WHERE `email` = '", $_REQUEST['email'], "'")->num_rows !== 0)
		$error = 'Cette adresse e-mail est déjà utilisée 🤔';
	// teste si le mot de passe n'est pas trop court
	else if (strlen($_REQUEST['password']) < 4)
		$error = 'Ton mot de passe est vraiment trop court 😵';
	// Teste si les mots de passe sont identiques
	else if ($_REQUEST['password'] !== $_REQUEST['password2'])
		$error = 'Ce ne sont pas les mêmes mots de passe 🥴';
	// Teste si le captcha a bien été résolu
	else if (defined('HCAPTCHA_SECRET') && (!isset($_REQUEST['h-captcha-response']) || empty($_REQUEST['h-captcha-response'])))
		$error = 'Il faut que tu complètes le captcha juste au dessus 👾';
	// Teste si le captcha a été résolu avec succès
	else if (defined('HCAPTCHA_SECRET') && !json_decode(file_get_contents('https://hcaptcha.com/siteverify?secret='.HCAPTCHA_SECRET.'&response='.$_POST['h-captcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']))->success)
		$error = 'La vérification de ton humanité à échouer, réessaye 👽';
	// Inscription
	else {
		$hashed_password = hash('sha512', $_POST['password']);
		request_database("INSERT INTO `USERS` (`email`, `name`, `password`, `firstName`, `lastName`) VALUES ('",$_REQUEST['email'],"', '",$_REQUEST['username'],"', '",$hashed_password,"', '",$_REQUEST['firstname'],"', '",$_REQUEST['lastname'],"');");
		session_start();
		$_SESSION['username'] = $_REQUEST['username'];
		$_SESSION['password'] = $hashed_password;
		if (isset($_REQUEST['go']))
			exit(header('Location: '.$_REQUEST['go']));
		if (!isset($_REQUEST['closeafter'])) {
			exit(header('Location: .'));
		}
		$close = true;
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Inscription | <?= $website ?></title>
		<link rel="stylesheet" href="style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script src="https://www.hCaptcha.com/1/api.js" async defer></script>
	</head>
	<body>
		<section class="floating container">
			<form method="POST" action="" class="form">
				<h1>Inscription</h1>
				<label for="username">Nom d'utilisateur</label>
				<input id="username" type="text" name="username" placeholder="Nom d'utilisateur (visible par tous)" <?php echo(isset($_REQUEST['username']) ? 'value="'.$_REQUEST['username'].'" ' : '') ?>required />
				<label for="username">Adresse e-mail</label>
				<input type="email" name="email" placeholder="Adresse e-mail (invisible pour les autres)" <?php echo(isset($_REQUEST['email']) ? 'value="'.$_REQUEST['email'].'" ' : '') ?>required />
				<label for="username">Mot de passe</label>
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password2" placeholder="Confirmation de mot de passe" required />
				<label for="username">Prénom et nom</label>
				<input type="text" name="firstname" placeholder="Prénom (invisible pour les autres)" <?php echo(isset($_REQUEST['firstname']) ? 'value="'.$_REQUEST['firstname'].'" ' : '') ?>required />
				<input type="text" name="lastname" placeholder="Nom de famille (invisible pour les autres)" <?php echo(isset($_REQUEST['lastname']) ? 'value="'.$_REQUEST['lastname'].'" ' : '') ?>required />
				<?php if(defined('HCAPTCHA_SECRET')) { ?>
					<div>
						<label>Vérification</label>
						<div class="h-captcha" data-sitekey="bdea39ea-e2c6-49a8-aff4-800d01b0d6ac"></div>
					</div>
				<?php } ?>
				<input type="submit" class="good" value="S'inscrire" />
				<?php if ($error !== null) { ?>
					<p class="error><?= $error ?></p>
				<?php } else if ($close) { ?>
					<script>window.close();</script>
					<p class="helper">Tu es maintenant inscrit(e), tu peux fermer cette onglet. 🎉</p>
				<?php } ?>
				<a class="large" href="login.php<?php echo isset($_REQUEST['go']) ? '?go='.urlencode($_REQUEST['go']) : (isset($_REQUEST['closeafter']) ? '?closeafter' : ''); ?>">👤 J'ai déjà un compte</a>
			</form>
		</section>
	</body>
</html>