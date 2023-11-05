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
            <h2 style="clear: both;">Applications connectées :</h2>
            <ul>
                <?php
                $tokens = sendRequest("SELECT * FROM `TOKENS` JOIN `APPS` ON app = id WHERE `user` = '".$user['id']."'");
                if ($tokens->num_rows == 0) echo "Aucune application connectée";
                else while ($token = $tokens->fetch_assoc()) {
                ?>
                    <li>
                        <a href="<?=$token['url']?>">
                            <img height="20" src="<?=$token['icon']?>" />
                            <?=$token['name']?>
                        </a>
                        depuis le
                        <time datetime="<?=$token['date']?>"><?=explode(' ', $token['date'])[0]?></time>
                        <a class="bad" href="disconnect.php?back&app=<?=$token['app']?>">Déconnecter</a>
                    </li>
                <?php } ?>
            </ul>
            <a class="bad" href="logout.php">🚪 Se déconnecter</a>
            <a class="bad" href="delete.php">🗑️ Supprimer mon compte</a>
            <a href=".">🏠 Retour à l'accueil</a>
        </section>
    </body>
</html>