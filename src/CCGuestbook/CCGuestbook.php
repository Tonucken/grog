<?php
/** Controller för gästbok
* @package GrogCore */
class CCGuestbook extends CObject implements IController {
	private $guestbookModel;
 	public function __construct() {
		parent::__construct();
		$this->guestbookModel = new CMGuestbook();
	}

	/** Implementerar interface IController och visa standardsida för gästboken */
	public function Index() {
		$this->views->SetTitle('Lydia Guestbook Example');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
			'entries'=>$this->guestbookModel->ReadAll(),
			'form_action'=>$this->request->CreateUrl('', 'handler')
			));
	}
 

	/** Hantera formulärposter */
	public function Handler() {
		if(isset($_POST['doAdd'])) {$this->guestbookModel->Add(strip_tags($_POST['newEntry']));}
		elseif(isset($_POST['doClear'])) {$this->guestbookModel->DeleteAll();}
		elseif(isset($_POST['doCreate'])) {$this->guestbookModel->Init();}           
		$this->RedirectTo($this->request->CreateUrl($this->request->controller));
	}
} // endclass 
