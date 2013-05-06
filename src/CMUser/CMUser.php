<?php
/** Modell för autenticerade användare
* @package GrogCore */
class CMUser extends CObject implements IHasSQL, ArrayAccess, IModule {

	public $profile;

	/** Constructor */
	public function __construct($grog=null) {
		parent::__construct($grog);
		$profile = $this->session->GetAuthenticatedUser();
		$this->profile = is_null($profile) ? array() : $profile;
		$this['isAuthenticated'] = is_null($profile) ? false : true;
		if(!$this['isAuthenticated']) {
			$this['id'] = 1;
			$this['acronym'] = 'anonomous';
		}
	}

	/** Implementerar ArrayAccess för $this->profile */
	public function offsetSet($offset, $value) {if (is_null($offset)) {$this->profile[] = $value;} else {$this->profile[$offset] = $value;}}
	public function offsetExists($offset) {return isset($this->profile[$offset]);}
	public function offsetUnset($offset) {unset($this->profile[$offset]);}
	public function offsetGet($offset) {return isset($this->profile[$offset]) ? $this->profile[$offset] : null;}

	/** Implementerar interface IModule. Hantera installation, uppdateringar och liknande. */
	public function Manage($action=null) {
		switch($action) {
		case 'install':
			try {
				$this->db->ExecuteQuery(self::SQL('drop table user2group'));
				$this->db->ExecuteQuery(self::SQL('drop table group'));
				$this->db->ExecuteQuery(self::SQL('drop table user'));
				$this->db->ExecuteQuery(self::SQL('create table user'));
				$this->db->ExecuteQuery(self::SQL('create table group'));
				$this->db->ExecuteQuery(self::SQL('create table user2group'));
				$this->db->ExecuteQuery(self::SQL('insert into user'), array('anonomous', 'Anonym, not authenticated', null, 'plain', null, null));
				$password = $this->CreatePassword('root');
				$this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'Admin', 'root@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
				$idRootUser = $this->db->LastInsertId();
				$password = $this->CreatePassword('doe');
				$this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
				$idDoeUser = $this->db->LastInsertId();
				$this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'Administratörsgruppen'));
				$idAdminGroup = $this->db->LastInsertId();
				$this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'Användargruppen'));
				$idUserGroup = $this->db->LastInsertId();
				$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
				$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
				$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
				return array('success', 'Databasens tabeller har skapats. Administratörskonto root:root och användarkonto doe:doe har lagts till.');
			} catch(Exception$e) {die("$e<br/>Misslyckades att öppna databas: " . $this->config['database'][0]['dsn']);}
			break;
		default:
			throw new Exception('Den här modulen stöder inte det.');
			break;
		}
	}
        
	/** Implementerar interface IHasSQL */
	public static function SQL($key=null) {
		$queries = array(
			'drop table user' 	=> "DROP TABLE IF EXISTS User;",
			'drop table group' 	=> "DROP TABLE IF EXISTS Groups;",
			'drop table user2group' => "DROP TABLE IF EXISTS User2Groups;",
			'create table user' 	=> "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
			'create table group' 	=> "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
			'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
			'insert into user' 	=> 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
			'insert into group' 	=> 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
			'insert into user2group' => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
			'check user password' 	=> 'SELECT * FROM User WHERE (acronym=? OR email=?);',
			'get group memberships' => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
			'update profile' 	=> "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
			'update password' 	=> "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
			);
		if(!isset($queries[$key])) {throw new Exception("No such SQL query, key '$key' was not found.");}
		return $queries[$key];
	}

	/** Login med autenticerad användare och lösenord. Lagra användarinformation i session */
	public function Login($akronymOrEmail, $password) {
		$user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
		$user = (isset($user[0])) ? $user[0] : null;
		if(!$user) {return false;}
		else if(!$this->CheckPassword($password, $user['algorithm'], $user['salt'], $user['password'])) {return false;}
		unset($user['algorithm']);
		unset($user['salt']);
		unset($user['password']);
		if($user) {
			$user['isAuthenticated'] = true;
			$user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
			foreach($user['groups'] as $val) {
				if($val['id'] == 1) {$user['hasRoleAdmin'] = true;}
				if($val['id'] == 2) {$user['hasRoleUser'] = true;}
			}
			$this->profile = $user;
			$this->session->SetAuthenticatedUser($this->profile);
		}
		return ($user != null);
	}
  
	/** Logga ut. Rensa både session och interna egenskaper */
	public function Logout() {
		$this->session->UnsetAuthenticatedUser();
		$this->profile = array();
		$this->AddMessage('success', "Du har loggat ut.");
	}
  
	/** Skapa ny användare */
	public function Create($acronym, $password, $name, $email) {
		$pwd = $this->CreatePassword($password);
		$this->db->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd['algorithm'], $pwd['salt'], $pwd['password']));
		if($this->db->RowCount() == 0) {
			$this->AddMessage('error', "Misslyckades att skapa användare.");
			return false;
		}
		return true;
	}
  
	/** Skapa lösenord */
	public function CreatePassword($plain, $algorithm=null) {
		$password = array(
			'algorithm'=>($algorithm ? $algoritm : CGrog::Instance()->config['hashing_algorithm']),
			'salt'=>null
			);
		switch($password['algorithm']) {
		case 'sha1salt': $password['salt'] = sha1(microtime()); $password['password'] = sha1($password['salt'].$plain); break;
		case 'md5salt': $password['salt'] = md5(microtime()); $password['password'] = md5($password['salt'].$plain); break;
		case 'sha1': $password['password'] = sha1($plain); break;
		case 'md5': $password['password'] = md5($plain); break;
		case 'plain': $password['password'] = $plain; break;
			default: throw new Exception('Okänd hashberäkning');
		}
		return $password;
	}
  
	/** Kontrollera om lösenordet stämmer */
	public function CheckPassword($plain, $algorithm, $salt, $password) {
		switch($algorithm) {
		case 'sha1salt': return $password === sha1($salt.$plain); break;
		case 'md5salt': return $password === md5($salt.$plain); break;
		case 'sha1': return $password === sha1($plain); break;
		case 'md5': return $password === md5($plain); break;
		case 'plain': return $password === $plain; break;
			default: throw new Exception('Okänd hashberäkning');
		}
	}
  
	/** Spara användarprofil till databasen och uppdatera i sessionen */
	public function Save() {
		$this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
		$this->session->SetAuthenticatedUser($this->profile);
		return $this->db->RowCount() === 1;
	}
    
	/** Ändra användares lösenord */
	public function ChangePassword($plain) {
		$password = $this->CreatePassword($plain);
		$this->db->ExecuteQuery(self::SQL('update password'), array($password['algoritm'], $password['salt'], $password['password'], $this['id']));
		return $this->db->RowCount() === 1;
	}
} // endclass
