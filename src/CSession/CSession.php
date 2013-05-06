<?php
/** Wrapper för sessionsvärden, läs och lagra till nästa sidladdning 
* @package GrogCore*/

class CSession {
	/** Members */
	private $key;
	private $data = array();
	private $flash = null;

	/** Constructor */
	public function __construct($key) {$this->key = $key;}

	/** Tilldela värden */
	public function __set($key, $value) {$this->data[$key] = $value;}

	/** Hämta värden */
	public function __get($key) {return isset($this->data[$key]) ? $this->data[$key] : null;}

	/** Hämta, tilldela eller avdela autenticerad användare */
	public function SetAuthenticatedUser($profile) {$this->data['authenticated_user'] = $profile;}
	public function UnsetAuthenticatedUser() {unset($this->data['authenticated_user']);}
	public function GetAuthenticatedUser() {return $this->authenticated_user;}

	/** Hämta eller tilldela flash, giltigt för en sidrequest */
	public function SetFlash($key, $value) { $this->data['flash'][$key] = $value; }
	public function GetFlash($key) { return isset($this->flash[$key]) ? $this->flash[$key] : null; }

	/** Lägg till meddelande som visas nästa sidladdning i flash */
	public function AddMessage($type, $message) {$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);}

	/** Hämta flash-meddelanden */
	public function GetMessages() {return isset($this->flash['messages']) ? $this->flash['messages'] : null;}

	/** Lagra värden i sessionen */
	public function StoreInSession() {$_SESSION[$this->key] = $this->data;}

	/** Lagra värden från aktuellt objekt till sessionen */
	public function PopulateFromSession() {
		if(isset($_SESSION[$this->key])) {
			$this->data = $_SESSION[$this->key];
			if(isset($this->data['flash'])) {
				$this->flash = $this->data['flash'];
				unset($this->data['flash']);
			}
		}
	}
} // endclass
