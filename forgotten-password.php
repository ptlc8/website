<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Mot de passe oublié | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
	</head>
	<body>
		<?php
		include('init.php');
		define('HCAPTCHA_SECRET', '0x80525437E4C3DfFC1282e614DA960ddb4A8249d5');
		
		if (isset($_REQUEST['token'])) {// vérification du token
			$result = sendRequest("SELECT * FROM FORGOTREQUEST JOIN USERS ON USERS.id = FORGOTREQUEST.userId WHERE token = '", $_REQUEST['token'], "'");
			if ($result->num_rows !== 0) {
				if (isset($_REQUEST['password'], $_REQUEST['password2'])) { // changement de mot de passe
					if (strlen($_REQUEST['password']) < 4) {
						queryNewPassword("Ton mot de passe est vraiment trop court 😵");
					} else if ($_REQUEST['password'] !== $_REQUEST['password2']) {
						queryNewPassword("Ce ne sont pas les mêmes mots de passe 🥴");
					} else {
						$hashed_password = hash('sha512', $_POST['password']);
						sendRequest("UPDATE `USERS` JOIN FORGOTREQUEST ON USERS.id = FORGOTREQUEST.userId SET `password` = '", $hashed_password, "' WHERE token = '", $_REQUEST['token'], "'");
						printMessage("Ton mot de passe a bien été modifier 🥳", "<a href=\"connect.php\">Je me reconnecte</a>");
					}
				} else { // demande du nouveau mot de passe
					queryNewPassword();
				}
			} else printMessage("Cette requête n'existe pas ou a expirée 🤕");
		} else {
			if (!isset($_REQUEST['username'],$_REQUEST['h-captcha-response'])) { // demande du compte
				queryUsername();
			} else if (empty($_REQUEST['h-captcha-response'])) {
				queryUsername("Il faut que tu complètes le captcha juste au dessus 👾");
			} else if (!json_decode(file_get_contents('https://hcaptcha.com/siteverify?secret='.HCAPTCHA_SECRET.'&response='.$_POST['h-captcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']))->success) {
				queryUsername("La vérification de ton humanité à échouer, réessaye 👽");
			} else { // envoi de l'e-mail
				$result = sendRequest("SELECT email, id FROM USERS WHERE name = '", $_REQUEST['username'], "'");
				if ($result->num_rows !== 0) {
					$account = $result->fetch_assoc();
					$email = $account['email'];
					do $token = createToken(32); while (sendRequest("SELECT * FROM FORGOTREQUEST WHERE token = ', $token, '")->num_rows !== 0);
					sendRequest("INSERT INTO `FORGOTREQUEST` (`userId`, `token`, `expire`) VALUES ('", $account['id'], "', '", $token, "', CURDATE() + INTERVAL 7 DAY)");
					mail($email, "<".$_SERVER['HTTP_HOST']."> Récupération de ton mot de passe", "<div style=\"text-align: center;\">Coucou petit galopin,<br />J'ai appris que tu avais perdu ton mot de passe,<br />C'est pas bien<br />Heureuseument je suis là<br />J'ai donc pour toi un petit lien :<br /><a href=\"http://".$_SERVER['HTTP_HOST']."/forgotten-password.php?token=".$token."\">CLIQUE-MOI !</a><br />Celui-ci te permettra de le changer<br />Mais attention, tu n'as que 7 jours<br />(c'est beaucoup)<br />Cordialement.<br />Devine qui suis-je,<br />Mais ne répond pas à ce message...</div>", "From:contact@".$_SERVER['HTTP_HOST']."\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n");
					printMessage("Un mail à été envoyé à l'adresse ".preg_replace('/(?<=.{4}).(?=.*@)/', '*', $email).". 😜", "Dans celui-ci vous trouverez un lien pour changer votre mot de passe.");
				} else {
					queryUsername("Le nom d'utilisateur est inconnu");
				}
			}
		}
		
		function queryUsername($error='') {
			?>
			<form method="POST" action="" class="form">
				<h1 class="title">Mot de passe oublié</h1>
				<input name="username" type="text" placeholder="Nom d'utilisateur" required />
				<div class="h-captcha" data-sitekey="bdea39ea-e2c6-49a8-aff4-800d01b0d6ac"></div>
				<input type="submit" value="Changer de mot de passe" />
				<?php if ($error!='') echo "<p style='color: red;'>$error</p>"; ?>
			</form>
			<?php
		}
		function queryNewPassword($error='') {
			?>
			<form method="POST" action="?token=<?php echo $_REQUEST['token']; ?>" class="form">
				<h1 class="title">Mot de passe oublié</h1>
				<input name="password" type="password" placeholder="Nouveau mot de passe" required />
				<input name="password2" type="password" placeholder="Confirmation du mot de passe" required />
				<input type="submit" value="Changer de mot de passe" />
				<?php if ($error!='') echo "<p style='color: red;'>$error</p>"; ?>
			</form>
			<?php
		}
		function printMessage($title, $msg='') {
			echo '<div class="menu"><h1 class="title">'.$title.'</h1>'.$msg.'</div>';
		}
		
		function createToken($length, $characters='azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN123456789-') {
			$token = '';
			for ($i = 0; $i < $length; $i++)
				$token .= $characters[random_int(0, strlen($characters)-1)];
			return $token;
		}
		
		?>
	</body>
</html>