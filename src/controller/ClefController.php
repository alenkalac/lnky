<?php 

namespace Lnky\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClefController {
	public function registerProcess(Request $req, Application $app) {
		\Clef\Clef::initialize(CLEF_APP, CLEF_SECRET);
		
		$code = $req->get('code');
		$CSRF = $app['csrf.token_manager']->getToken("SIGNUP_CLEF_CSRF");
		$state = $CSRF->getValue();

		$this->validate_state($app, $state);

		try {
			$response = \Clef\Clef::get_login_information($code);
			$user_information = $response->info;

			$user = $this->getUserWithClefID($app, $user_information->id);

			if(!$user) 
		  		$user = $this->insertUser($app, $user_information);

			$app['session']->set('user', $user['id']);

		} catch (Exception $e) {
		  	return new RedirectResponse('/');
		}
		finally {
			return new RedirectResponse($app['url_generator']->generate('publisher'));
		}
	}

	/**
	* Validates the CSRF token. 
	* 
	* The Silex Application
	* @param Application $app
	*
	* The CSRF token.
	* Clef refers to it as 'state'
	* @param String $state
	*
	* @return boolean
	*/
	private function validate_state($app, $state) {
		$sState = $app['session']->get('state');
	    $is_valid = isset($sState) && strlen($sState) > 0 && $sState == $state;
	    if (!$is_valid) {
	        header('HTTP/1.0 403 Forbidden');
	        echo "The state parameter didn't match what was passed in to the Clef button.";
	        exit;
	    } else {
	        $app['session']->remove('state');
	    }
	    return $is_valid;
	}

	/**
	 * Returns the user from database, based on the clef ID
	 * 
	 * Silex Application
	 * @param Application $app
	 * 
	 * User ID aquired from CLEF. Unique to each user
	 * @param int $id
	 *
	 * @return array
	 */
	private function getUserWithClefID($app, $id) {
		$query = $app['db']->prepare("SELECT * FROM users WHERE clef_id = :ID");
		$query->execute(['ID' => $id]);
		$result = $query->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	private function insertUser($app, $info) {
		$query = $app['db']->prepare("INSERT INTO users VALUES(NULL, :EMAIL, '', :CLEFID, 1)");
		$query->execute([
			'EMAIL' => $info->email,
			'CLEFID' =>  $info->id,
		]);

		$user = [];
		$user['id'] = $query->lastInsertId();

		return $user;
	}
}