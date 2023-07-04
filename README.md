# Site web

Site web regroupant mes différents projets.

Actuellement déployé sur [ambi.dev](https://ambi.dev).


## Lancer en local

Il est possible de lancer le projet en local.
Pour cela il faut faudra PHP et MySQL.
 - cloner le projet
 - créer un fichier src/credentials.php contenant identifiants de la base de données, sous cette forme :
```php
<?php
define('DB_HOST', 'my_host');
define('DB_USER', 'my_user');
define('DB_PASS', 'my_password');
define('DB_NAME', 'my_dbname');
define('HCAPTCHA_SECRET', '0x123456789aBcDeF'); // ligne optionnel
?>
```
 - créer un fichier src/sitemap.json en prenant exemple sur [sitemap.json.example](sitemap.json.example)
 - exécuter dans la base de données le script SQL [init.sql](init.sql)
 - lancer le serveur php