<?php

if ($database['sql']['dms'] === 'sql') {
	require_once('lib/Db/Dbsql.php');
} else if ($database['sql']['dms'] === 'postgre') {
	require_once('lib/Db/DbPostgre.php');
} else if ($database['sql']['dms'] === 'oracle') {
	require_once('lib/Db/DbOracle.php');
} else if ($database['sql']['dms'] === 'msssql') {
	require_once('lib/Db/DbMssql.php');
} else {
	require_once('lib/Db/DbSqlite.php');	
}

require_once('lib/Query.php');

abstract class Model {

	protected $db;
	protected $query;
	protected $table;

	function __construct($table) {
		global $database;
		$this->table = $table;
		$this->db = new Db($database['sql']['host'], $database['sql']['username'], $database['sql']['password'], $database['sql']['database'], $database['sql']['charset']);
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

	public function verifyToken() {
		
	}

}

?>