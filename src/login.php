<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Connexion | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<section class="floating container">
			<form method="post" action="">
				<h1>Connexion</h1>
				<p class="helper">(Les identifiants de l'ex agnd.fr, de cy-hub.fr et de ambi.dev sont les mêmes)</p>
				<label for="username">Nom d'utilisateur</label>
				<input id="username" name="username" type="text" placeholder="Nom d'utilisateur" <?php echo(isset($_REQUEST['username']) ? 'value="'.$_REQUEST['username'].'" ' : '') ?> autocomplete="name" autofocus />
				<label for="password">Mot de passe</label>
				<input id="password" name="password" type="password" placeholder="Mot de passe" autocomplete="current-password" />
				<input type="submit" value="Se connecter" class="good" />
				
				<?php
				include("api/init.php");
				if (isset($_REQUEST['username'], $_POST['password'])) {
					$hashed_password = hash('sha512', $_POST['password']);
					if (sendRequest("SELECT * FROM USERS WHERE `name` = '", $_REQUEST['username'], "' and `password` = '", $hashed_password, "'")->num_rows === 0) {
						echo('<p class="error">Nom d\'utlisateur ou mot de passe invalide. 😱</p>');
					} else {
						$_SESSION['username'] = $_REQUEST['username'];
						$_SESSION['password'] = $hashed_password;
						if (isset($_REQUEST['go'])) {
							header('Location: '.$_REQUEST['go']);
						} else if (isset($_REQUEST['closeafter'])) { ?>
							<script>window.close();</script>
							<p class="helper">Vous êtes connecté(e), vous pouvez fermer cet onglet. 🎉</p>
						<?php } else{
							header('Location: .');
						}
					}
				}
				?>
				<a class="large" href="register.php<?php echo isset($_REQUEST['go']) ? '?go='.urlencode($_REQUEST['go']) : (isset($_REQUEST['closeafter']) ? '?closeafter' : '?'); ?>">Je me connecte pour la première fois</a>
				<a class="large" href="forgotten-password.php">J'ai oublié mon mot de passe</a>
			</form>
		</section>
	</body>
</html>
