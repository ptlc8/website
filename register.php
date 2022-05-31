<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Inscription | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
	</head>
	<body>
		<section id="connect" class="floating container">
			<form method="POST" action="" class="form">
				<h1>Inscription</h1>
				<div>
					<label for="username">Nom d'utilisateur</label>
					<input id="username" type="text" name="username" placeholder="Nom d'utilisateur (visible par tous)" <?php echo(isset($_REQUEST['username']) ? 'value="'.$_REQUEST['username'].'" ' : '') ?>required />
				</div>
				<div>
					<label for="username">Adresse e-mail</label>
					<input type="email" name="email" placeholder="Adresse e-mail (invisible pour les autres)" <?php echo(isset($_REQUEST['email']) ? 'value="'.$_REQUEST['email'].'" ' : '') ?>required />
				</div>
				<div>
					<label for="username">Mot de passe</label>
					<input type="password" name="password" placeholder="Mot de passe" required />
					<input type="password" name="password2" placeholder="Confirmation de mot de passe" required />
				</div>
				<div>
					<label for="username">Prénom et nom</label>
					<input type="text" name="firstname" placeholder="Prénom (invisible pour les autres)" <?php echo(isset($_REQUEST['firstname']) ? 'value="'.$_REQUEST['firstname'].'" ' : '') ?>required />
					<input type="text" name="lastname" placeholder="Nom de famille (invisible pour les autres)" <?php echo(isset($_REQUEST['lastname']) ? 'value="'.$_REQUEST['lastname'].'" ' : '') ?>required />
				</div>
				<?php
				include('init.php');
				if(defined('HCAPTCHA_SECRET')) { ?>
				<div>
					<label for="username">Vérification</label>
					<div class="h-captcha" data-sitekey="bdea39ea-e2c6-49a8-aff4-800d01b0d6ac"></div>
				</div>
				<?php } ?>
				<input type="submit" value="S'inscrire" />
				
				<?php
				if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['password2'], $_REQUEST['firstname'], $_REQUEST['lastname'])) {
					// teste si le nom d'utilisateur est déjà pris
					if (sendRequest("SELECT * FROM USERS WHERE `name` = '", $_REQUEST['username'], "'")->num_rows !== 0)
						echo '<p class="error">Ce nom d\'utilisateur est déjà utilisé 😦';
					// teste si l'adresse e-mail est déjà prise
					else if (sendRequest("SELECT * FROM USERS WHERE `email` = '", $_REQUEST['email'], "'")->num_rows !== 0)
						echo '<p class="error">Cette adresse e-mail est déjà utilisée 🤔';
					// teste si le mot de passe n'est pas trop court
					else if (strlen($_REQUEST['password']) < 4)
						echo '<p class="error">Ton mot de passe est vraiment trop court 😵';
					// Teste si les mots de passe sont identiques
					else if ($_REQUEST['password'] !== $_REQUEST['password2'])
						echo '<p class="error">Ce ne sont pas les mêmes mots de passe 🥴';
					// Teste si le nom d'utilisateur ne contient pas de XSS
					/*else if($_REQUEST['username']!=htmlspecialchars($_REQUEST['username'])){ // Noramalement les injections XSS avec le pseudo sont gérer (du moins sur Poképrof)
						echo '<p class="error">Votre nom contient des caractères invalides 👺!';*/
					// Teste si le captcha a bien été résolu
					else if (!isset($_REQUEST['h-captcha-response']) || empty($_REQUEST['h-captcha-response']))
						echo '<p class="error">Il faut que tu complètes le captcha juste au dessus 👾';
					// Teste si le captcha a été résolu avec succès
					else if (defined('HCAPTCHA_SECRET') && !json_decode(file_get_contents('https://hcaptcha.com/siteverify?secret='.HCAPTCHA_SECRET.'&response='.$_POST['h-captcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']))->success)
						echo '<p class="error">La vérification de ton humanité à échouer, réessaye 👽';
					// Inscription
					else {
						$hashed_password = hash('sha512', $_POST['password']);
						sendRequest("INSERT INTO `USERS` (`email`, `name`, `password`, `firstName`, `lastName`) VALUES ('",$_REQUEST['email'],"', '",$_REQUEST['username'],"', '",$hashed_password,"', '",$_REQUEST['firstname'],"', '",$_REQUEST['lastname'],"');");
						$_SESSION['username'] = $_REQUEST['username'];
						$_SESSION['password'] = $hashed_password;
						if (isset($_REQUEST['go'])) {
							header('Location: '.$_REQUEST['go']);
						} else if (isset($_REQUEST['closeafter'])) {
							echo('<script>window.close();</script><p class="helper">Tu es maintenant inscrit(e), tu peux fermer cette onglet. 🎉</p>');
						} else {
							header('Location: .');
						}
					}
				}
				?>
				
				<p><a href="connect.php<?php echo isset($_REQUEST['go']) ? '?go='.urlencode($_REQUEST['go']) : (isset($_REQUEST['closeafter']) ? '?closeafter' : ''); ?><?php echo isset($_REQUEST['icon']) ? '?icon='.urlencode($_REQUEST['icon']) : ''; ?>">J'ai déjà un compte</a></p>
			</form>
			<?php if (isset($_REQUEST['icon'])) { ?><img width="200" src="<?= $_REQUEST['icon'] ?>" /><?php } ?>
		</section>
	</body>
</html>