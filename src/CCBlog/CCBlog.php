<?php
/** Blog controller för att visa innehåll av typen 'post' (alternativet är 'page')
* @package GrogCore */
class CCBlog extends CObject implements IController {
	public function __construct() {parent::__construct();}

	/** Visa innehåll av typen 'post' */
	public function Index() {
		$content = new CMContent();
		$this->views->SetTitle('Blog')
		->AddInclude(__DIR__ . '/index.tpl.php', array('contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),));
	}
} // endclass
