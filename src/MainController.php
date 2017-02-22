<?php 
	namespace lnky;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;

	class MainController {

		public function indexPage(Request $req, Application $app) {
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