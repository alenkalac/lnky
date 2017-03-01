<?php 
	$app->get('/', getCtrlPath('MainController', 'indexPage'))->bind("index");

	$app->get('/publisher', getCtrlPath('MainController', "pubPage"))->bind("publisher");

	$app->get('/signup', getCtrlPath('MainController', "signupPage"))->bind("signup");

	$app->get('/login', getCtrlPath('MainController', 'loginPage'))->bind('login');

	$app->get('/logout', getCtrlPath('MainController', 'logoutProcess'))->bind('logout');

	$app->post('/signup', getCtrlPath('MainController', "signupProcess"));
	$app->post('/login', getCtrlPath('MainController', 'loginProcess'));

	//clef 
	$app->get('/clef/redirect', getCtrlPath('ClefController', "registerProcess"));

	function getCtrlPath($class, $function) {
		return "Lnky\Controller\\$class::$function";
	}