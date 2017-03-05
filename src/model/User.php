<?php 
namespace Lnky\Model;

class User {
	private $id;
	private $email;
	private $display_name;
	private $clef_id;
	private $verified;
	private $password;

	protected $database;

	public function __construct($app = null) {
		$this->database = $app['db'];
	}

	/*
	public public getUserByID($id) {
		if($database == null)
			return -1;

		$query = $database->prepare("SELECT u.id, u.email, u.display_name, u.password, u.clef_id, u.verified,
											u.logout_time, u.until_pay, u.admin,u.banned, l.link, l.real_url, 
											l.adblocker, l.active, s.extension, d.url, a.api_key, 
										(SELECT COUNT(*) FROM click_details WHERE click_details.link = l.link) as 
											total_clicks FROM users as u
										INNER JOIN links as l on l.user = u.id
										INNER JOIN short_links as s on s.link = l.link
										INNER JOIN domains as d on s.domain = d.domain
										INNER JOIN api_keys as a on a.user = u.id");
	}
	*/

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$value = ['email' => $email];
		$where = ['id' => $this->$id];
		$database->update('users', $value, $where);
	}

	public function getId() {
		return $this->id;
	}

	public function isPasswordValid($password) {
		return password_verify($password, $this->password);
	}

	static function getAllUsers($database = null) {
		if($this->database == null && $database == null) 
			return [];
		$this->database = $database;
		$query = $database->prepare("SELECT * FROM users");
		$query->execute();
		$result = $query->fetchAll(\PDO::FETCH_CLASS, 'Lnky\Model\User');
		return $result;
	}
}


?>