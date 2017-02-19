<?php 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider($db_config));
$app->register(new Silex\Provider\TwigServiceProvider(), [
	'twig.path' => $templatesDirectory,
	'twig.options' => []
]);