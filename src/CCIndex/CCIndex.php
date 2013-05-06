<?php
/** Standard controller layout
* @package GrogCore */
class CCIndex extends CObject implements IController {
	public function __construct() { parent::__construct(); }
  
	/** Interface IController */
	public function Index() {
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$this->views->SetTitle('Index')
		->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers'=>$controllers), 'sidebar');
        }
} // endclass