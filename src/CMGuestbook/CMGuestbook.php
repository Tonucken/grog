<?php
/** Modell för gästboken 
* @package GrogCore */
class CMGuestbook extends CObject implements IHasSQL, IModule {
	
	public function __construct() {parent::__construct();}

	/** Implementerar interface IHasSQL */
	public static function SQL($key=null) {
		$queries = array(
			'create table guestbook' => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
			'insert into guestbook' => 'INSERT INTO Guestbook (entry) VALUES (?);',
			'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
			'delete from guestbook' => 'DELETE FROM Guestbook;',
			);
		if(!isset($queries[$key])) {throw new Exception("Ingen SQL-träff enligt '$key' kunde hittas.");}
		return $queries[$key];
	}

	/** Implementerar interface IModule. Hantera installation, uppdateringar och liknande */
	public function Manage($action=null) {
		switch($action) {
		case 'install':
			try {
				$this->db->ExecuteQuery(self::SQL('create table guestbook'));
				return array('success', 'Databasens tabeller har skapats (eller lämnats orörda om de redan existerade).');
			} catch(Exception$e) {die("$e<br/>Misslyckades att öppna databasen: " . $this->config['database'][0]['dsn']);}
			break;
		default:
			throw new Exception('Modulen stöder inte det.');
			break;
		}
	}
	  
	/** Nytt inlägg till gästboken och spara till databasen */
	public function Add($entry) {
		$this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
		$this->session->AddMessage('success', 'Inlägg postat.');
		if($this->db->rowCount() != 1) {die('Inlägget har inte lagts till i gästbokens databas.');}
	}
  
	/** Radera alla inlägg från gästboken och databasen */
	public function DeleteAll() {
		$this->db->ExecuteQuery(self::SQL('Radera från gästboken'));
		$this->session->AddMessage('info', 'Alla inlägg har raderats från databasen.');
	}
    
	/** Läs alla inlägg */
	public function ReadAll() {
		try {return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));}
		catch(Exception $e) {return array();}
	}
} // endclass 
