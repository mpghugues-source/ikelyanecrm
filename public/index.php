<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * IKELYANEMED — Plateforme SaaS Médicale
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'PHP ' . $minPhpVersion . '+ requis. Version actuelle : ' . PHP_VERSION;
    exit(1);
}

// HEAD → GET : CI4 ne route pas HEAD automatiquement vers les routes GET
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'HEAD') {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

// Chemin vers ce fichier (front controller)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Charger l'autoloader Composer (nécessaire avant DotEnv)
if (is_file(FCPATH . '../vendor/autoload.php')) {
    require FCPATH . '../vendor/autoload.php';
}

// Charger la config des chemins
require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

// Charger le bootstrap du framework
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
