<?php 
	$app->get('/', 'lnky\MainController::indexPage')->bind("index");

	$app->get('/{id}', 'lnky\MainController::redirectPage');