<?php
/** API för databasen
* @package GrogCore */
class CDatabase {
	/** Members */
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	private static $queries = array();

	/** Constructor */
	public function __construct($dsn, $username = null, $password = null, $driver_options = null) {
		$this->db = new PDO($dsn, $username, $password, $driver_options);
		$this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/** Attribut på databasen */
	public function SetAttribute($attribute, $value) {return $this->db->setAttribute($attribute, $value);}

	/** Getters */
	public function GetNumQueries() { return self::$numQueries; }
	public function GetQueries() { return self::$queries; }

	/** Kör select-query med argument, och presentera resultatet */
	public function ExecuteSelectQueryAndFetchAll($query, $params=array()){
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query;
		self::$numQueries++;
		$this->stmt->execute($params);
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/** Kör SQL-query */
	public function ExecuteQuery($query, $params = array()) {
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query;
		self::$numQueries++;
		return $this->stmt->execute($params);
	}

	/** Presentera senast inlagda ID */
	public function LastInsertId() {return $this->db->lastInsertid();}


	/** Presentera rader som påverkats av senaste INSERT, UPDATE, DELETE */
	public function RowCount() {return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();}
} // endclass
