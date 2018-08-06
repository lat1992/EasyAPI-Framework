<?php

class Db {

	private $mysql;

	function __construct($host, $username, $password, $database, $charset) {
		$this->mysql = new mysqli($host, $username, $password, $database);
		$this->mysql->set_charset($charset);
	}

	function __destruct() {
		$this->mysql->close();
	}

	public function exec($sql) {
		return $this->mysql->query($sql);
	}

	public function realEscapeString($str) {
		return $this->mysql->real_escape_string($str);
	}

	public function getInsertDb() {
		return $this->mysql->insert_id;
	}

}

?>