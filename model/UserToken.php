<?php

class UserToken extends Model {

	function __construct() {
		parent::__construct('USERTOKEN');
	}

	public function getIdByToken($token) {
		$this->query->select("id")
		->where("token LIKE ". $this->string($token));
		return $this->db->execQuery();
	}

}

?>