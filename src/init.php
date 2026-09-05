<?php
const SITE_NAME = 'Ambi.dev';
const SITE_AUTHOR = 'Ambi alias PTLC';

// si le fichier config.php existe, on l'inclut
@include('config.php');

// obtenir une variable de configuration
function get_config($name) {
	if (defined($name) && !empty(constant($name)))
		return constant($name);
	return getenv($name) ?: null;
}

// obtenir le nom de domaine
function get_host() {
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	return preg_match('/^[a-z0-9.-]+(?::[0-9]+)?$/i', $host) ? $host : 'localhost';
}

// obtenir le protocole
function get_protocol() {
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
		return 'https';
	if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
		return 'https';
	return 'http';
}

// obtenir l'URL du serveur d'authentification
function get_auth_url() {
	return get_config('AUTH_URL') ?? '';
}

// obtenir la liste des projets
function get_sitemap() {
	$defaultPath = is_file(__DIR__.'/sitemap.json') ? __DIR__.'/sitemap.json' : __DIR__.'/../sitemap.json.prod';
	$path = get_config('SITEMAP_PATH') ?: $defaultPath;
	if (!is_file($path) || !is_readable($path))
		return [];

	$data = json_decode(file_get_contents($path));
	return is_array($data) ? $data : [];
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

function get_project_types($project) {
	$types = $project->type ?? [];
	$types = is_array($types) ? $types : [$types];
	$types = array_map(fn($type) => trim(strtolower((string) $type)), $types);
	return array_values(array_unique(array_filter($types)));
}

// générer des composants RGB basés sur un texte
function textToRgb($text) {
	$pos = ord(strtoupper($text[0])) - 65; // 0-25 (A-Z)
	
	$r = round(255 - abs($pos - 4) * 10);
	$g = round(175 - abs($pos - 18) * 5);
	$b = round(80 - abs($pos - 2) * 3);
	
	$r = max(0, min(255, $r));
	$g = max(0, min(255, $g));
	$b = max(0, min(255, $b));
	
	return "$r, $g, $b";
}
?>
