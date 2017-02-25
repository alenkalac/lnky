<?php 
	$app->get('/', 'lnky\MainController::indexPage')->bind("index");

	$app->get('/publisher', 'lnky\MainController::pubPage')->bind("publisher");

	$app->get('/signup', 'lnky\MainController::signupPage')->bind("signup");

	$app->post('/signup', 'lnky\MainController::signupProcess');