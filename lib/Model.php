<?php

require_once('lib/Db.php');
require_once('lib/Query.php');

abstract class Model {

	protected $db;
	protected $query;
	protected $table;

	function __construct($table) {
		global $database;
		$this->table = $table;
		$this->db = new Db($database['mysql']['host'], $database['mysql']['username'], $database['mysql']['password'], $database['mysql']['database'], $database['mysql']['charset']);
		$this->query = new Query($table);
	}

	public function execQuery() {
		$result = $this->db->exec($this->query->build());
		$this->query->clear();
		return $result;
	}

	public function string($str) {
		return "'". $this->db->realEscapeString($str) ."'";
	}

}

?>