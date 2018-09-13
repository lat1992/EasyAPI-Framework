<?php

require_once('model/User.php');

class Profil {

	private $user_infomation;

	function _construct() {
		$this->user_information = new UserInformation();
	}

}

?>