<?php
$website = $_SERVER['HTTP_HOST'];
include("api/init.php");
$user = login_from_session();
if ($user == null) {
    exit(header("Location: login.php?go=".urlencode($_SERVER['REQUEST_URI'])));
}
$tokens = request_database("SELECT * FROM `TOKENS` JOIN `APPS` ON app = id WHERE `user` = '".$user['id']."'")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Compte | <?= $website ?></title>
		<link rel="stylesheet" href="style.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
        <section class="floating container">
            <h1>Ton compte</h1>
            <div>
                <img width="200" height="200" class="avatar" src="avatar.php" alt="Gravatar" style="float: right;" />
                <table>
                    <tr><td>Identifiant :</td><td><?= $user['id'] ?></td></tr>
                    <tr><td>Nom d'utilisateur : </td><td><?= htmlspecialchars($user['name']) ?></td></tr>
                    <tr><td>Adresse mail : </td><td><?= htmlspecialchars($user['email']) ?></td></tr>
                    <tr><td>Prénom : </td><td><?= htmlspecialchars($user['firstName']) ?></td></tr>
                    <tr><td>Nom : </td><td><?= htmlspecialchars($user['lastName']) ?></td></tr>
                </table>
            </div>
            <h2 style="clear: both;">Applications connectées :</h2>
            <ul>
                <?php if (count($tokens) == 0) { ?>
                    Aucune application connectée
                <?php } else foreach ($tokens as $token) { ?>
                    <li>
                        <a href="<?= $token['url'] ?>">
                            <img height="20" src="<?= $token['icon'] ?>" />
                            <?= $token['name'] ?>
                        </a>
                        depuis le
                        <time datetime="<?= $token['date'] ?>"><?= explode(' ', $token['date'])[0] ?></time>
                        <a class="bad" href="disconnect.php?back&app=<?= $token['app'] ?>">Déconnecter</a>
                    </li>
                <?php } ?>
            </ul>
            <a class="bad" href="logout.php">🚪 Se déconnecter</a>
            <a class="bad" href="delete.php">🗑️ Supprimer mon compte</a>
            <a href=".">🏠 Retour à l'accueil</a>
        </section>
    </body>
</html>