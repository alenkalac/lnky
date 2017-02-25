<?php

	$SANDBOX = true;

	//twig template directory
	$templatesDirectory = __DIR__ . '/../templates';

	//database settings
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