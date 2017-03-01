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