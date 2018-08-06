<?php

class Query {

	private $sql;
	private $table;

	function __construct($table = "") {
		$this->sql = "";
		$this->setTable($table);
	}

	public function add($sql) {
		$this->sql .= $sql;
	}

	public function set($sql) {
		$this->sql = $sql;
	}

	public function clear() {
		$this->sql = "";
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function select() {
		$columns = func_get_args();
		foreach ($columns as $key => $column) {
			if (strpos($column, '.') === false) {
				if ($columns[$key] !== '*') {
					$columns[$key] =  $this->table .".". $columns[$key] ." AS ". $columns[$key];
				}
			}
		}
		$this->add("SELECT ". implode(",", $columns) ." FROM ". $this->table);
		return $this;
	}

	public function insert($columns_values) {
		$this->add("INSERT INTO ". $this->table ." (". implode(",", array_keys($columns_values)) .") VALUES (". implode(",", $columns_values) .")");
		return $this;
	}

	public function update($columns_values) {
		$column_value = array();
		foreach ($columns_values as $column => $value) {
			$column_value = array_push($column_value, $column ." = ". $value);
		}
		$this->add("UPDATE ". $this->table ." SET ". implode(",", $column_value));
		return $this;
	}

	public function delete() {
		$this->add("DELETE FROM ". $this->table);
		return $this;
	}

	public function inner($table, $first_key = "", $second_key = "") {
		$this->add(" INNER JOIN ". $table);
		if ($first_key !== "" && $second_key !== "") {
			$this->add(" ON ". $this->table .".". $first_key ." = ". $table .".". $second_key);
		}
		return $this;
	}

	public function left($table, $first_key = "", $second_key = "") {
		$this->add(" LEFT JOIN ". $table);	
		if ($first_key !== "" && $second_key !== "") {
			$this->add(" ON ". $this->table .".". $first_key ." = ". $table .".". $second_key);
		}
		return $this;
	}

	public function right($table, $first_key = "", $second_key = "") {
		$this->add(" RIGHT JOIN ". $table);
		if ($first_key !== "" && $second_key !== "") {
			$this->add(" ON ". $this->table .".". $first_key ." = ". $table .".". $second_key);
		}
		return $this;
	}

	public function join($table, $first_key = "", $second_key = "") {
		return $this->inner($table, $first_key, $second_key);
	}

	public function on($condition) {
		$this->add(" ON ". $condition);
		return $this;
	}

	public function where($condition) {
		$this->add(" WHERE ". $condition);
		return $this;
	}

	public function and($condition) {
		$this->add(" AND ". $condition);
		return $this;
	}

	public function or($condition) {
		$this->add(" OR ". $condition);
		return $this;
	}

	public function groupBy() {
		$this->add(" GROUP BY ". implode(",", func_get_args()));
		return $this;
	}

	public function having($condition) {
		$this->add(" HAVING ". $condition);
		return $this;
	}

	public function union($table = "", $sql = "") {
		if ($table !== "") {
			$this->setTable($table);
		}
		$this->add(" UNION ". $sql);
		return $this;
	}

	public function orderBy() {
		$this->add(" ORDER BY ". implode(",", func_get_args()));
		return $this;
	}

	public function limit($quantity, $offset = 0) {
		$this->add(" LIMIT ". $quantity ." OFFSET ". $offset);
		return $this;
	}

	public function build() {
		return $this->sql;
	}

}

?>