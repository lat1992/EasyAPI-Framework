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
		if ($password_hash === $user['password']) {
			$token = substr(md5(microtime()), rand(0, 26), 20);
			$this->user_token->save([
				'user_id' => $user['id'],
				'token:s' => $token
			]);
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

	public function logout($params) {
		$token = $params['token'];
		$result = $this->user_token->getIdByToken($token);
		$user_token = $result->fetch_assoc();
		$this->user_token->delete($user_token['id']);
		return (object)array(
			'code' => 200,
			'message' => 'LogoutSuccessful'
		);
	}

	public function createAccount($params) {
		$username_email = $params['username_email'];
		$password = $params['password'];
		$salt =  substr(md5(microtime()), rand(0, 26), 6);
		$password_hash = crypt($password, $salt);
		$result = $this->user->save([
			'username_email' => $username_email,
			'password' => $password_hash,
			'salt' => $salt
		]);
		if ($result !== false) {
			return (object)array(
				'code' => 201,
				'message' => 'CreateAccountSuccessful'
			);
		}
		return (object)array(
			'code' => 400,
			'message' => 'CreateAccountUnsuccessful'
		);
	}

}

?>