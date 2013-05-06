<?php
/** Controller för siten */
class CCMycontroller extends CObject implements IController {
	/** Constructor */
	public function __construct() {parent::__construct();}
	
	/** Me-sidan */
	public function Index() {
		$content = new CMContent(4);
		$this->views->SetTitle('Om mig')
		->AddInclude(__DIR__ . '/page.tpl.php', array('content' => $content,));
	}

	/** Grogen */
	public function Blog() {
		$content = new CMContent();
		$this->views->SetTitle('Min Grog')
                ->AddInclude(__DIR__ . '/blog.tpl.php', array('contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),));
        }

        /** Grogbook */
        public function Guestbook() {
        	$guestbook = new CMGuestbook();
        	$form = new CFormMyGuestbook($guestbook);
        	$status = $form->Check();
        	if($status === false) {
        		$this->AddMessage('notice', 'Formuläret kunde inte behandlas.');
        		$this->RedirectToControllerMethod();
        	} else if($status === true) {$this->RedirectToControllerMethod();}
    
        	$this->views->SetTitle('Min Grog-book')
        	->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
        		'entries'=>$guestbook->ReadAll(),
        		'form'=>$form,
        		));
        }
}

/** Formulär för Grogbook */
class CFormMyGuestbook extends CForm {
	/** Properties */
	private $object;

	/** Constructor */
	public function __construct($object) {
		parent::__construct();
		$this->object = $object;
		$this->AddElement(new CFormElementTextarea('data', array('label'=>'Skriv inlägg:')))
		->AddElement(new CFormElementSubmit('add', array('callback'=>array($this, 'DoAdd'), 'callback-args'=>array($object))));
	}
  
	/** Callback för formuläret till databas. */
	public function DoAdd($form, $object) {return $object->Add(strip_tags($form['data']['value']));}
}
