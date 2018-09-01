<?php

class User extends Model {

	function __construct() {
		parent::__construct('USER');
	}

	public function save($username, $password_hash, $email, $salt) {
		$this->query->insert([
			"username" => $this->string($username),
			"password" => $this->string($password_hash),
			"email" => $this->string($email),
			"salt" => $this->string($salt)
		]);
		$this->execQuery();
		return $this->db->getInsertId();
	}

	public function getUserById($id) {
		$this->query->select("*")->where("id = ". $id);
		return $this->execQuery();
	}

	public function getUserByUsernameOrEmail($username_email) {
		$this->query->select("id", "password", "salt", "email")
		->where("username LIKE ". $this->string($username_email))
		->or("email LIKE ". $this->string($username_email));
		return $this->execQuery();
	}

	public function updateEmailById($id, $email) {
		$this->query->update([
			"email" => $this->string($email)
		])->where("id = ". $id);
		return $this->execQuery();
	}

	public function updatePasswordById($id, $new_password_hash) {
		$this->query->update([
			"password" => $this->string($new_password_hash)
		])->where("id = ". $id);
		return $this->execQuery();
	}

}

?>