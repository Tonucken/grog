<?php
/** Content controller 
* @package GrogCore */
class CCContent extends CObject implements IController {
	/** Constructor */
	public function __construct() { parent::__construct(); }
	
	/** Lista allt innehåll */
	public function Index() {
		$content = new CMContent();
		$this->views->SetTitle('Content Controller')
		->AddInclude(__DIR__ . '/index.tpl.php', array('contents' => $content->ListAll(),));
	}
	
	/** Redigera innehåll, alt. förbered att skapa nytt om argument saknas */
	public function Edit($id=null) {
		$content = new CMContent($id);
		$form = new CFormContent($content);
		$status = $form->Check();
		if($status === false) {
			$this->AddMessage('notice', 'Formuläret kunde inte bearbetas.');
			$this->RedirectToController('edit', $id);
		} else if($status === true) {$this->RedirectToController('edit', $content['id']);}
    
		$title = isset($id) ? 'Edit' : 'Create';
		$this->views->SetTitle("$title content: ".htmlEnt($content['title']))
		->AddInclude(__DIR__ . '/edit.tpl.php', array(
			'user'=>$this->user,
			'content'=>$content,
			'form'=>$form,
			));
	}
	
	/** Skapa nytt innehåll */
	public function Create() {$this->Edit();}
	
	/** Initiera databasen */
	public function Init() {
		$content = new CMContent();
		$content->Init();
		$this->RedirectToController();
	}
} // endclass
