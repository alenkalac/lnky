<?php 
	namespace Lnky\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Security\Csrf\CsrfToken;
	use Lnky\Model\User;
	use PDO;

	class MainController {

		public function indexPage(Request $req, Application $app) {
			//return $this->getNextShortURL("zz");
			return $app['twig']->render('index.twig', []);
		}

		public function testPage(Request $req, Application $app) {
			
			$r = $req->get('id');

			return $r;

			//return $app['twig']->render('index.twig', []);
		}

		public function redirectPage(Request $req, Application $app) {
			$link = $req->get('link');
			$ip = $this->getLocationInfoByIp();
			
			$query = $app['db']->prepare("SELECT * FROM links WHERE link = :LINK");
			$query->execute([
				'LINK' => $link,
			]);

			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return new RedirectResponse($result['real_url']);
		}

		public function publisherPage(Request $req, Application $app) {
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

			$query = $app['db']->prepare("INSERT INTO users VALUES(NULL, :EMAIL, '' ,:PASSWORD, 0, 1, 0,0,0,0)");
			$query->execute([
				"EMAIL" => $email,
				"PASSWORD" => $pass_hash,
			]);

			return new RedirectResponse("/");

		}

		public function signupPage(Request $req, Application $app) {
			$token_key = $app['csrf.token_manager']->refreshToken('SIGNUP_CSRF');
			$token_clef = $app['csrf.token_manager']->refreshToken('SIGNUP_CLEF_CSRF');
			$app['session']->set('state', $token_clef->getValue());
			$args = [
				'signup' => 'active',
				'token' => $token_key->getValue(),
			];
			return $app['twig']->render('signup.twig', ['args' => $args]);
		}

		public function loginProcess(Request $req, Application $app) {
			$email = $req->get('email');
			$pass = $req->get('password');
			$token = $req->get('token_value');

			$token_valid = $app['csrf.token_manager']->isTokenValid(new CsrfToken('LOGIN_CSRF', $token));


			if(!$token_valid)
				return new RedirectResponse($app['url_generator']->generate('login'));

			$query = $app['db']->prepare('SELECT * FROM users WHERE email = :EMAIL');
			$query->execute(['EMAIL' => $email]);
			$query->setFetchMode(PDO::FETCH_CLASS, 'Lnky\Model\User');
			$user = $query->fetch();

			if(!$user)
				return new RedirectResponse($app['url_generator']->generate('login'));

			$pass_valid = $user->isPasswordValid($pass);

			if(!$pass_valid)
				return new RedirectResponse($app['url_generator']->generate('login'));

			$app['session']->set('user', $user->getId());
			return new RedirectResponse($app['url_generator']->generate('publisher'));
		}

		public function loginPage(Request $req, Application $app) {
			$token_key = $app['csrf.token_manager']->refreshToken('LOGIN_CSRF');
			$token_clef = $app['csrf.token_manager']->refreshToken('SIGNUP_CLEF_CSRF');
			$app['session']->set('state', $token_clef->getValue());

			$args = [
				'login' => 'active',
				'token' => $token_key->getValue(),
			];

			return $app['twig']->render('login.twig', ['args' => $args]);
		}

		public function logoutProcess(Request $req, Application $app) {
			$app['session']->clear();
			return new RedirectResponse('/');
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