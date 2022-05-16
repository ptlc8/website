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
		<form method="POST" action="" class="form">
			<h1 class="title">Inscription</h1>
			<input type="text" name="username" placeholder="Nom d'utilisateur (visible par tous)" <?php echo(isset($_REQUEST['username']) ? 'value="'.$_REQUEST['username'].'" ' : '') ?>required />
			<input type="email" name="email" placeholder="Adresse e-mail (invisible pour les autres)" <?php echo(isset($_REQUEST['email']) ? 'value="'.$_REQUEST['email'].'" ' : '') ?>required />
			<input type="password" name="password" placeholder="Mot de passe" required />
			<input type="password" name="password2" placeholder="Confirmation de mot de passe" required />
			<input type="text" name="firstname" placeholder="Prénom (invisible pour les autres)" <?php echo(isset($_REQUEST['firstname']) ? 'value="'.$_REQUEST['firstname'].'" ' : '') ?>required />
			<input type="text" name="lastname" placeholder="Nom de famille (invisible pour autres)" <?php echo(isset($_REQUEST['lastname']) ? 'value="'.$_REQUEST['lastname'].'" ' : '') ?>required />
			<div class="h-captcha" data-sitekey="bdea39ea-e2c6-49a8-aff4-800d01b0d6ac"></div>
			<input type="submit" value="S'inscrire" />
			
			<?php
			
			include('init.php');
			define('HCAPTCHA_SECRET', '0x80525437E4C3DfFC1282e614DA960ddb4A8249d5');
			
			// fonction d'erreur client
			function echoError($error) {
				echo "<p style='color: red;'>$error</p>";
			}
			
			if (!(isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['password2'], $_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['h-captcha-response']))) return;
			if (sendRequest("SELECT * FROM USERS WHERE `name` = '", $_REQUEST['username'], "'")->num_rows !== 0) {
				echoError("Ce nom d'utilisateur est déjà utilisé 😦");
			}
			else if (sendRequest("SELECT * FROM USERS WHERE `email` = '", $_REQUEST['email'], "'")->num_rows !== 0) {
				echoError("Cette adresse e-mail est déjà utilisée 🤔");
			}
			else if (strlen($_REQUEST['password']) < 4) {
				echoError("Ton mot de passe est vraiment trop court 😵");
			}
			else if ($_REQUEST['password'] !== $_REQUEST['password2']) {
				echoError("Ce ne sont pas les mêmes mots de passe 🥴");
			}/*else if($_REQUEST['username']!=htmlspecialchars($_REQUEST['username'])){ // Noramalement les injections XSS avec le pseudo sont gérer (du moins sur Poképrof)
				echoError("Votre nom contient des caractères invalides 👺!");
			}*/
			else if (!isset($_REQUEST['h-captcha-response']) || empty($_REQUEST['h-captcha-response'])) {
				echoError("Il faut que tu complètes le captcha juste au dessus 👾");
			} else if (!json_decode(file_get_contents('https://hcaptcha.com/siteverify?secret='.HCAPTCHA_SECRET.'&response='.$_POST['h-captcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']))->success) {
				echoError("La vérification de ton humanité à échouer, réessaye 👽");
			}else {
				$hashed_password = hash('sha512', $_POST['password']);
				sendRequest("INSERT INTO `USERS` (`email`, `name`, `password`, `firstName`, `lastName`) VALUES ('",$_REQUEST['email'],"', '",$_REQUEST['username'],"', '",$hashed_password,"', '",$_REQUEST['firstname'],"', '",$_REQUEST['lastname'],"');");
				$_SESSION['username'] = $_REQUEST['username'];
				$_SESSION['password'] = $hashed_password;
				if (isset($_REQUEST['go'])) {
					echo("<script>window.location.replace(decodeURIComponent('".$_REQUEST['go']."'))</script");
				} else if (isset($_REQUEST['closeafter'])) {
					echo("<script>window.close();</script>");
				} else {
					echo("<script>window.location.replace('/');</script>");
				}
			}
			
			?>
			
			<p><a href="connect.php<?php echo isset($_REQUEST['invite']) ? '?invite='.$_REQUEST['invite'] : ''; ?>">J'ai déjà un compte</a></p>
		</form>
	</body>
</html>