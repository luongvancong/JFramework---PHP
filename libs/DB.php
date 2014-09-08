<?php

class DB {

	public $dbh = null;

	private static $instance;

	final private function __construct() {

		$config_db = require_once BASE_PATH . 'app/config/database.php';
		$db_host     = $config_db['mysql']['db_host'];
		$db_name     = $config_db['mysql']['db_name'];
		$db_user     = $config_db['mysql']['db_user'];
		$db_password = $config_db['mysql']['db_password'];

		$this->dbh = new PDO('mysql:host='. $db_host .';dbname='. $db_name .';charset=utf8', $db_user, $db_password);
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}

	/**
	 * Get instance
	 * @return object DB instance
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}


	/**
	 * Execute a query statament
	 *
	 * @param  string $sql
	 * @param  array  $params
	 * @return $statement
	 */
	public function query($sql, $params = array()) {
		$statement = $this->dbh->prepare($sql);

		foreach($params as $key => &$value) {
			$statement->bindParam(":" . $key, $value);
		}

		$statement->execute();

		// Write log query for debug in localhost
		if(isLocalhost()) {
			logs('query', $this->getQuery($sql, $params));
		}

		return $statement;
	}


	/**
	 * Get last insert ID
	 *
	 * @return integer
	 */
	public function getLastInsertId() {
		return $this->dbh->lastInsertId();
	}



	/**
	 * Get Affected rows
	 *
	 * @param  statement $statement
	 * @return integer
	 */
	public function getAffectedRows($statement) {
		return $statement->rowCount();
	}


	/**
	 * Execute update statement
	 *
	 * @param  string $table
	 * @param  array  $data
	 * @param  array  $where
	 * @return integer - Affected rows
	 */
	public function update($table, array $data, array $where) {

		$params = array();

		$sql = "UPDATE $table SET ";

		foreach($data as $field => $value) {
			$params[$field] = $value;
			$sql .= $field . '=:' . $field;
		}

		foreach($where as $field => $value) {
			$params[$field] = $value;
			$sql .= ' WHERE ' . $field . '= :' . $field;
		}

		$result = $this->query($sql, $params);

		return $result->rowCount();

	}


	/**
	 * Execute insert statement
	 *
	 * @param  string $table
	 * @param  array $data
	 * @return integer - Last insert ID
	 */
	public function insert($table, $data) {
		$params = array();

		$sql_fields = '(';

		$sql_values = ' VALUES (';

		foreach($data as $field => $value) {
			$params[$field] = $value;
			$sql_fields .= $field . ',';
			$sql_values .= ':' . $field . ',';
		}

		$sql_fields = trim($sql_fields, ',') . ')';
		$sql_values = trim($sql_values, ',') . ')';

		$sql = 'INSERT INTO ' . $table . $sql_fields . $sql_values;

		$result = $this->query($sql, $params);

		return $this->getLastInsertId();
	}


	/**
	 * Get query original
	 *
	 * @param  string $query
	 * @param  array $data
	 * @return string
	 */
	public function getQuery($query, $data) {
		$indexed = $data == array_values($data);

      foreach($data as $k => $v) {
         if(is_string($v)) $v = "'$v'";
         if($indexed) $query = preg_replace('/\:*/', $v, $query, 1);
         else $query = str_replace(":$k",$v,$query);
      }

      return $query;
	}

	/**
	 * Prevent clone
	 */
	private function __clone() {}


}