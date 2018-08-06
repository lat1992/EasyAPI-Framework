<?php

require_once('model/User.php');

class Auth {

	private $user;
	private $user_token;

	function _construct() {
		$this->user = new User();
		$this->user_token = new UserToken();
	}

	public function login($params) {
		$username_email = $params['username_email'];
		$result = $this->user->getUserByUsernameOrEmail($username_email);
		$user = $result->fetch_assoc();
		$password_hash = crypt($password, $user['salt']);
		if (strcmp($password_hash, $user['password']) === 0) {
			$token = substr(md5(microtime()), rand(0, 26), 20);
			$this->user_token->save($user['id'], $token);
			return (object)array(
				'code' => 200,
				'user_id' => $user['id'],
				'token' => $token
			);
		}
		return (object)array(
			'code' => 403,
			'error' => 'Auth::login()',
			'message' => 'WrongPassword'
		);
	}

	public function logout() {
		
	}
	
}

?>