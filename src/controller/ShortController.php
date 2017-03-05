<?php 
namespace Lnky\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PDO;

class ShortController {

	//readable characters l I 0 O 1 
	private $allowed_chars = 'ABCDEFGHJKLMNPQRSTUVWXYXZabcdefghijkmnopqrstuvwxyz23456789';

	public function getShortLink(Request $req, Application $app) {
		if($app['session']->get('user', 0) == 0)
				return new Response("Not Logged In", Response::HTTP_SERVICE_UNAVAILABLE);

		//escape these
		$real_addr = $req->get('u');
		$adb = 1;
		$monitize = $req->get('monitize');
		$user = $app['session']->get('user');

		$app['db']->beginTransaction();

		try {
			$query = $app['db']->prepare("INSERT INTO links VALUES(NULL, NULL, :USER, :REAL_ADDR, :ADB, 1)");
			$query->bindParam(":USER", $user);
			$query->bindParam(":REAL_ADDR", $real_addr);
			$query->bindParam(":ADB", $adb);
			$query->execute();

			$last_id = $app['db']->lastInsertId();

			do {
				$link = $this->getShortenedURLFromID($last_id, $this->allowed_chars);
				//$link = "asdasda";
				$check = $app['db']->prepare("SELECT COUNT(id) as count FROM links WHERE link = :LINK");
				$check->execute([
					"LINK" => $link,
				]);
				$result = $check->fetch(PDO::FETCH_ASSOC);
			} while($result['count'] > 0);
			
			$update = $app['db']->prepare("UPDATE links SET link = :LINK where id = :LASTID");
			$update->execute([
				"LINK" => $link,
				"LASTID" => $last_id,
			]);

			$app['db']->commit();

			$arr = ['status' => 200,'link' => $link];

		} catch(PDOException $e) {
			$app['db']->rollback();
			$arr = ['status' => 501,'error' => 'Something went wrong'];
			
		}

		return $app->json($arr);
	}

	function getShortenedURLFromID ($integer, $base)
	{
		$base = str_shuffle($base);
		$length = strlen($base);
		$out = null;

		while($integer > $length - 1)
		{
			$out = @$base[fmod($integer, $length)] . $out;
			$integer = floor( $integer / $length );
		}
		return @$base[$integer] . $out ;
	}

}


?>