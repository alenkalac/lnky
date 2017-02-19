<?php

	$SANDBOX = true;

	//twig template directory
	$templatesDirectory = __DIR__ . '/../templates';

	//database settings
	if($SANDBOX) {
		$db_config = array(
			'db.options' => [
						'dbname'=> 'lnky',
						'host' => 'localhost:3307',
						'pass' => '',
						'user' => 'root',
						'driver' => 'mysql_pdo',
					]
				);
		}


?>
