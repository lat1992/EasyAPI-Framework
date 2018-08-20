<?php

class UserToken extends Model {

	function __construct() {
		parent::__construct('USERTOKEN');
	}

	public function save($user_id, $token) {
		$this->query->select("id")
		->where("user_id = ". $user_id);
		$tmp = $this->execQuery();
		$this->query->clear();
		if ($row = $tmp->fetch_assoc()) {
			$this->query->update("token" => $this->string($token))
			->where("id" = $row['id']);
		} else {
			$this->query->insert("user_id" => $user_id, "token" => $this->string($token));
		}
		$this->execQuery();
		if (isset($row) === true)
			return $row['id'];
		return $this->db->getInsertId();
	}

	public function getIdByToken($token) {
		$this->query->select("id")
		->where("token LIKE ". $this->string($token));
		return $this->db->execQuery();
	}

	public function delete($id) {
		$this->query->delete()
		->where("id = ". $id);
		return $this->db->execQuery();
	}

}

?>