<?php 
	namespace Lnky\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Security\Csrf\CsrfToken;

	class MainController {

		public function indexPage(Request $req, Application $app) {
			//$app['csrf.token_manager']->generateCsrfToken('CSRF');
			//$app['csrf.token_manager']->refreshToken('CSRF');
			//PRINT_R($app['csrf.token_manager']->getToken('CSRF'));
			//DIE();
			return $app['twig']->render('index.twig', []);
		}

		public function redirectPage(Request $req, Application $app) {
			$id = $req->get('id');
			print_r($this->getLocationInfoByIp());
			die();
			//die($this->getLocationInfoByIp());

			//get IP
			//detect country
			//redirect
		}

		public function pubPage(Request $req, Application $app) {
			if($app['session']->get('user', 0) == 0)
				return new RedirectResponse("/");
			return $app['twig']->render('publisher.twig', ["numdays" => date('t')]);
		}

		public function signupProcess(Request $req, Application $app) {
			$error = [];
			$email = $req->get('email');
			$pass1 = $req->get('password');
			$pass2 = $req->get('re-password');
			$token = $req->get('token_value');

			//CHECK CSRF
			$token_valid = $app['csrf.token_manager']->isTokenValid(new CsrfToken('SIGNUP_CSRF', $token));

			if(!$token_valid) {
				$error['bad_token'] = 1;
			}

			//TODO: VERIFY EMAIL VALIDITY
			if(!$this->isValidEmail($email)) {
				$error['bad_email'] = 1;
			}

			//TODO: VERIFY PASSWORDS FOR MATCH
			if(strcmp($pass1, $pass2) != 0) {
				$error['bad_password'] = 1;
			}

			if(!empty($error)) {
				$token_key = $app['csrf.token_manager']->refreshToken('SIGNUP_CSRF');
				$app['session']->set('state', $token_key->getValue());
				$args = [
					'email' => $email,
					'pass1' => $pass1,
					'pass2' => $pass2,
					'token' => $token_key->getValue(),
				];

				return $app['twig']->render("signup.twig", ['args' => $args, 'error' => $error]);
			}

			$pass_hash = password_hash($pass1, PASSWORD_BCRYPT);

			$query = $app['db']->prepare("INSERT INTO users VALUES(NULL, :EMAIL, :PASSWORD, 1)");
			$query->execute([
				"EMAIL" => $email,
				"PASSWORD" => $pass_hash,
			]);

			return new RedirectResponse("/");

		}

		public function signupPage(Request $req, Application $app) {
			$token_key = $app['csrf.token_manager']->refreshToken('SIGNUP_CSRF');
			$app['session']->set('state', $token_key->getValue());
			$args = [
				'signup' => 'active',
				'token' => $token_key->getValue(),
			];
			return $app['twig']->render('signup.twig', ['args' => $args]);
		}

		private function isValidEmail($email) {
			return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
		}

		private function getLocationInfoByIp(){
		    $client  = @$_SERVER['HTTP_CLIENT_IP'];
		    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		    $remote  = @$_SERVER['REMOTE_ADDR'];
		    $result  = array('countryCode'=>'', 'countryName'=>'', 'city'=>'');
		    if(filter_var($client, FILTER_VALIDATE_IP)){
		        $ip = $client;
		    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
		        $ip = $forward;
		    }else{
		        $ip = $remote;
		    }

		    //$ip = "86.44.141.86";

		    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
		    if($ip_data && $ip_data->geoplugin_countryName != null){
		        $result['countryCode'] = $ip_data->geoplugin_countryCode;
		         $result['countryName'] = $ip_data->geoplugin_countryName;
		        $result['city'] = $ip_data->geoplugin_city;
		    }
		    return $result;
		}

	}

?>