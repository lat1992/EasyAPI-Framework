<?php

class Db {

	private $sql;

	function __construct($host, $username, $password, $database, $charset) {
		$this->sql = new mysqli($host, $username, $password, $database);
		$this->sql->set_charset($charset);
		//$this->sql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$this->sql->autocommit(FALSE);
	}

	function __destruct() {
		$this->sql->close();
	}

	public function exec($sql) {
		return $this->sql->query($sql);
	}

	public function realEscapeString($str) {
		return $this->sql->real_escape_string($str);
	}

	public function getInsertDb() {
		return $this->sql->insert_id;
	}

	public function commit() {
		return $this->sql->commit();
	}

	public function rollback() {
		return $this->sql->rollback();
	}

}

?>