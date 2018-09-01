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

	public function save($columns_values) {
		$cv = new stdClass();
		foreach ($columns_values as $column => $value) {
			if ($column !== 'id') {
				if (($pos = strpos($column, ':s')) !== false) {
					$cv[substr($column, 0, $pos + 1)] = $this->string($value);
				} else {
					$cv[$column] = $value;
				}
			}
		}
		if (isset($columns_values['id'])) {
			$this->query->update($cv)
			->where("id" = $columns_values['id']);
		} else {
			$this->query->insert($cv);
			return $this->db->getInsertId();
		}
		return $this->db->execQuery();
	}

	public function delete($id) {
		$this->query->delete()
		->where("id = ". $id);
		return $this->db->execQuery();
	}

}

?>