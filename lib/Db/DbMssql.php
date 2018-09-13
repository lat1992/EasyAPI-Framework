<?php

class Db {

	private $conn;

	function __construct($host, $username, $password, $database, $charset) {
		$connectionInfo = array("Database" => $database, "UID" => $username, "PWD" => $password, "CharacterSet" => $charset);
		$this->conn = sqlsrv_connect($host, $connectionInfo);
	}

	function __destruct() {
		sqlsrv_close($this->conn);
	}

	private function ms_escape_string($data) {
		if (isset($data) === false || empty($data) === true) {
			return '';
		}
		if (is_numeric($data) === true) {
			return $data;
		}
		$non_displayables = array(
			'/%0[0-8bcef]/',
			'/%1[0-9a-f]/',
			'/[\x00-\x08]/',
			'/\x0b/',
			'/\x0c/',
			'/[\x0e-\x1f]/'
		);
		foreach ($non_displayables as $regex) {
			$data = preg_replace($regex, '', $data);
		}
		$data = str_replace("'", "''", $data);
		return $data;
    }

	public function exec($sql) {
		return sqlsrv_query($this->conn, $sql);
	}

	public function realEscapeString($str) {
		return $this->ms_escape_string($str);
	}

	public function getInsertDb($queryID = NULL) {
		if ($queryID === NULL)
			return -1;
        sqlsrv_next_result($queryID);
        sqlsrv_fetch($queryID);
        return sqlsrv_get_field($queryID, 0);
	}

}

?>