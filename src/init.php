<?php
// si le fichier config.php existe, on l'inclut
@include('config.php');

// obtenir une variable de configuration
function get_config($name) {
	if (defined($name) && !empty(constant($name)))
		return constant($name);
	return getenv($name) ?? null;
}

// obtenir le nom de domaine
function get_host() {
	return $_SERVER['HTTP_HOST'];
}

// obtenir le protocole
function get_protocol() {
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
		return 'https';
	if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
		return 'https';
	return 'http';
}

// obtenir le nom du site
function get_site_name() {
	return get_config('SITE_NAME') ?? get_host();
}

// obtenir les informations du site
function get_site_data() {
	$site_data = new stdClass();
	$site_data->description = get_config('SITE_DESCRIPTION') ?? '';
	$site_data->keywords = get_config('SITE_KEYWORDS') ?? '';
	$site_data->author = get_config('SITE_AUTHOR') ?? '';
	$site_data->copyright = '© '.date('Y').' '.$site_data->author.' - Tous droits réservés';
	return $site_data;
}

// obtenir l'URL du serveur d'authentification
function get_auth_url() {
	return get_config('AUTH_URL') ?? '';
}

// obtenir la liste des projets
function get_sitemap() {
	return json_decode(file_get_contents('sitemap.json'));
}

// convertir un texte en slug
function slugify($text) {
	return strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text));
}

// obtenir les projets mis en avant
function get_featured_projects() {
	$sitemap = get_sitemap();
	$featured = [];
	foreach ($sitemap as $project) {
		if ($project->featured ?? false)
			$featured[] = $project;
	}
	return $featured;
}
?>
