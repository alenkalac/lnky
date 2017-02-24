<?php 
	$app->get('/', 'lnky\MainController::indexPage')->bind("index");

	$app->get('/publisher', 'lnky\MainController::pubPage')->bind("publisher");