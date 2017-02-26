<?php 
	$app->get('/', getCtrlPath('MainController', 'indexPage'))->bind("index");

	$app->get('/publisher', getCtrlPath('MainController', "pubPage"))->bind("publisher");

	$app->get('/signup', getCtrlPath('MainController', "signupPage"))->bind("signup");

	$app->post('/signup', getCtrlPath('MainController', "signupProcess"));

	//clef 
	$app->get('/clef/redirect', getCtrlPath('ClefController', "registerProcess"));

	function getCtrlPath($class, $function) {
		return "Lnky\Controller\\$class::$function";
	}