<?php 
	$app->get('/', getCtrlPath('MainController', 'indexPage'))->bind("index");

	$app->get('/{link}', getCtrlPath('MainController', 'redirectPage'));

	$app->get('/p/publisher', getCtrlPath('MainController', "publisherPage"))->bind("publisher");

	$app->get('/p/signup', getCtrlPath('MainController', "signupPage"))->bind("signup");

	$app->get('/p/login', getCtrlPath('MainController', 'loginPage'))->bind('login');

	$app->get('/p/logout', getCtrlPath('MainController', 'logoutProcess'))->bind('logout');

	$app->post('/signup', getCtrlPath('MainController', "signupProcess"));
	$app->post('/login', getCtrlPath('MainController', 'loginProcess'));
	$app->post('/short', getCtrlPath('ShortController', 'getShortLink'));

	//clef 
	$app->get('/clef/redirect', getCtrlPath('ClefController', "registerProcess"));

	function getCtrlPath($class, $function) {
		return "Lnky\Controller\\$class::$function";
	}