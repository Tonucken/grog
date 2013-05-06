<?php
/** Model för innehåll lagrat i databas 
* @package GrogCore */
class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule {
	/** Properties */
	public $data;

	/** Constructor */
	public function __construct($id=null) {
		parent::__construct();
		if($id) {$this->LoadById($id);}
		else {$this->data = array();}
	}

	/** Implementerar ArrayAccess för $this->data */
	public function offsetSet($offset, $value) {if (is_null($offset)) {$this->data[] = $value;} else {$this->data[$offset] = $value;}}
	public function offsetExists($offset) {return isset($this->data[$offset]);}
	public function offsetUnset($offset) {unset($this->data[$offset]);}
	public function offsetGet($offset) {return isset($this->data[$offset]) ? $this->data[$offset] : null;}


	/** Implementerar interface IHasSQL */
	public static function SQL($key=null, $args=null) {
		$order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
		$order_by = isset($args['order-by']) ? $args['order-by'] : 'id';
		$queries = array(
			'drop table content' 	=> "DROP TABLE IF EXISTS Content;",
			'create table content' 	=> "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
			'insert content' 	=> 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
			'select * by id' 	=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
			'select * by key' 	=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=? AND deleted IS NULL;',
			'select * by type' 	=> "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
			'select *' 		=> 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE deleted IS NULL;',
			'update content' 	=> "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
			'update content as deleted' => "UPDATE Content SET deleted=datetime('now') WHERE id=?;",
			);
		if(!isset($queries[$key])) {throw new Exception("Ingen sådan söksträng med '$key' hittades.");}
		return $queries[$key];
	}

	/** Implementerar interface IModule. Hantera installation, uppdateringar och liknande */
	public function Manage($action=null) {
		switch($action) {
		case 'install':
			try {
				$this->db->ExecuteQuery(self::SQL('drop table content'));
				$this->db->ExecuteQuery(self::SQL('create table content'));
				$this->db->ExecuteQuery(self::SQL('insert content'), array('testar-post', 'post', 'Testar post', "Detta är ett demo-inlägg av typen post som används för dina blog-inlägg.", 'plain', $this->user['id']));
				$this->db->ExecuteQuery(self::SQL('insert content'), array('testar-page', 'page', 'Testar page', "Detta är en demo-sida av typen page som används för att lägga upp innehåll som nya sidor", 'plain', $this->user['id']));
				$this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'post', 'Testar post med BBCode', "Detta är en demo-sida av typen page, med filter BBCode.\n\n[b]Fetstil[/b] och [i]kursiv[/i] och en [url=http://dbwebb.se]klickbar länk[/url] till dbwebb.se.", 'bbcode', $this->user['id']));
				$this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Testar page with HTMLPurifier', "Detta är en demo-sida av typen page, som kan användas för att skapa nya sidor med innehåll att sedan länka till i menyn. Här tillåts viss HTML genom att ange <a href='http://htmlpurifier.org/'>HTMLPurify</a> som filter. \n\n<b>Fetstil</b>, <i>kursiv</i> och en <a href='http://dbwebb.se'>länk</a> till dbwebb.se. Varken php eller javascript tillåts", 'htmlpurify', $this->user['id']));
				return array('success', 'Databas och tabeller tillagda och första default inlägg har publicerats.');
			} catch(Exception$e) {die("$e<br/>Misslyckades öppna databas: " . $this->config['database'][0]['dsn']);}
			break;
		default:
			throw new Exception('Modulen saknar stöd för det.');
			break;
		}
	}
  
	/** Spara innehåll. Uppdatera befintligt id om det finns, annars skapa nytt */
	public function Save() {
		$msg = null;
		if($this['id']) {
			$this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
			$msg = 'uppdaterat';
		} else {
			$this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
			$this['id'] = $this->db->LastInsertId();
			$msg = 'skapat';
		}
		$rowcount = $this->db->RowCount();
		if($rowcount) {$this->AddMessage('success', "Har {$msg} innehållet '" . htmlEnt($this['key']) . "'.");}
		else {$this->AddMessage('error', "Har inte {$msg} innehållet '" . htmlEnt($this['key']) . "'.");}
		return $rowcount === 1;
	}
    
	/** Radera innehåll. Aktivera papperskorg enligt borttagningsdatum */
	public function Delete() {
		if($this['id']) {$this->db->ExecuteQuery(self::SQL('uppdatera innehåll som raderat'), array($this['id']));}
		$rowcount = $this->db->RowCount();
		if($rowcount) {$this->AddMessage('success', "Innehåll '" . htmlEnt($this['key']) . "' markerat som raderat.");}
		else {$this->AddMessage('error', "Misslyckades markera innehåll '" . htmlEnt($this['key']) . "' som markerat.");}
		return $rowcount === 1;
	}
    
	/** Ladda innehåll enligt id */
	public function LoadById($id) {
		$res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
		if(empty($res)) {
			$this->AddMessage('error', "Det gick inte att ladda id '$id'.");
			return false;
		} else {$this->data = $res[0];}
		return true;
	}
    
	/** Lista allt innehåll */
	public function ListAll($args=null) {
		try {
			if(isset($args) && isset($args['type'])) {return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));}
			else {return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));}
		} catch(Exception $e) {
			echo $e;
			return null;
		}
	}
  
	/** Filtrera innehållet enligt angivna filter. */
	public static function Filter($data, $filter) {
		switch($filter) {
		case 'htmlpurify': $data = nl2br(CHTMLPurifier::Purify($data)); break;
		case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;
		case 'plain':
			default: $data = nl2br(makeClickable(htmlEnt($data))); break;
		}
		return $data;
	}
  
	/** Presentera filtrerat innehåll */
	public function GetFilteredData() {return $this->Filter($this['data'], $this['filter']);}
} // endclass
