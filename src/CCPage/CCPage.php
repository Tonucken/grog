<?php
/** Controller för 'page'-sidor (alternativet är 'post')
* @package GrogCore */
class CCPage extends CObject implements IController {
	/** Constructor */
	public function __construct() {parent::__construct();}
	
	/** Visa tom sida */ 
	public function Index() {
		$content = new CMContent();
		$this->views->SetTitle('Page')
                ->AddInclude(__DIR__ . '/index.tpl.php', array('content' => null,));
        }

        /** Visa sida */
        public function View($id=null) {
        	$content = new CMContent($id);
        	$this->views->SetTitle('Page: '.htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/index.tpl.php', array('content' => $content,));
        }
} // endclass 
