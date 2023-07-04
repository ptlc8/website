<?php
include("api/init.php");
$user = login();
if ($user == null) {
    header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI']));
    exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Compte | <?=$_SERVER['HTTP_HOST']?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
        <section class="floating container">
            <div>
                <h1>Ton compte</h1>
                <div>
                    <img width="200" height="200" class="avatar" src="avatar.php" alt="Gravatar" style="float: right;" />
                    <table>
                        <tr><td>Identifiant :</td><td><?=$user['id']?></td></tr>
                        <tr><td>Nom d'utilisateur : </td><td><?=htmlspecialchars($user['name'])?></td></tr>
                        <tr><td>Adresse mail : </td><td><?=htmlspecialchars($user['email'])?></td></tr>
                        <tr><td>Prénom : </td><td><?=htmlspecialchars($user['firstName'])?></td></tr>
                        <tr><td>Nom : </td><td><?=htmlspecialchars($user['lastName'])?></td></tr>
                    </table>
                </div>
                <h2 style="clear: both;">Compte connecté à :</h2>
                <ul>
                    <?php foreach (sendRequest("SELECT * FROM `TOKENS` WHERE `user` = '".$user['id']."'") as $token) { ?>
                    <li>
                        <?=$token['app']?> depuis le
                        <time datetime="<?=$token['date']?>"><?=explode(' ', $token['date'])[0]?></time>
                        <a class="bad" href="disconnect.php?back&app=<?=$token['app']?>">Déconnecter</a></li>
                    <?php } ?>
                </ul>
                <div>
                    <a class="large bad" href="logout.php">🚪 Se déconnecter</a>
                    <a class="large bad" href="delete.php">🗑️ Supprimer mon compte</a>
                    <a class="large" href=".">🏠 Retour à l'accueil</a>
                </div>
            </div>
        </section>
    </body>
</html>