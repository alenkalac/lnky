<?php 
	$app->get('/', function() use($app) {
		$uid = uniqid();
		die($uid );
		return $app['twig']->render('index.html.twig', []);
	});

	$app->get("/{id}", function ($id) use ($app) {

		$uid = uniqid();
		echo $uid;
		die();

	});