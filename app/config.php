<?php



//twig template directory
$templatesDirectory = __DIR__ . '/../templates';

DEFINE('CLEF_APP', 'cb86b4307c32f0e177fb66facfc68e9f');
DEFINE('CLEF_SECRET', 'df39c879410d2a2b2f1f462b08e805b7');

// true = localhost server
// false = live server
$SANDBOX = true;
if($SANDBOX) 
	$db_config = array(
		'db.options' 	=> [
			'driver' 	=> 'pdo_mysql',
			'host' 		=> 'localhost',
			'dbname' 	=> 'lnky',
			'user' 		=> 'root',
			'password' 	=> '',
			'charset' 	=> 'utf8',
			'port'		=> '3307'
		]
	);


?>