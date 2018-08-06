<?php

require_once('model/User.php');

class Profil {

	private $user;

	function _construct() {
		$this->user = new User();
	}

}

?>