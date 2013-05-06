<?php
/** Kontrollpanel för administratör
* @package GrogCore */
class CCAdminControlPanel extends CObject implements IController {
	/** Constructor */
	public function __construct() {parent::__construct();}

	/** Visa användarprofil */
	public function Index() {
		$this->views->SetTitle('Kontrollpanel för administratörer');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php');
	}
} // endclass
