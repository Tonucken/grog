<?php
/** Hantera och analysera moduler 
* @package GrogCore */
class CCModules extends CObject implements IController {
	public function __construct() { parent::__construct(); }
	
	/** Visa index-sida för controllern */
	public function Index() {
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$allModules = $modules->ReadAndAnalyse();
		$this->views->SetTitle('Hantera moduler')
                ->AddInclude(__DIR__ . '/index.tpl.php', array('controllers'=>$controllers), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
        }
        
        /** Visa index-sida för installationen */
        public function Install() {
        	$modules = new CMModules();
        	$results = $modules->Install();
        	$allModules = $modules->ReadAndAnalyse();
        	$this->views->SetTitle('Installera moduler')
                ->AddInclude(__DIR__ . '/install.tpl.php', array('modules'=>$results), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
        }
        
        /** Visa enskilda moduler och dess delar */
        public function View($module) {
        	if(!preg_match('/^C[a-zA-Z]+$/', $module)) {throw new Exception('Ogiltiga tecken i modulnamnet.');}
        	$modules = new CMModules();
        	$controllers = $modules->AvailableControllers();
        	$allModules = $modules->ReadAndAnalyse();
        	$aModule = $modules->ReadAndAnalyseModule($module);
        	$this->views->SetTitle('Hantera moduler')
                ->AddInclude(__DIR__ . '/view.tpl.php', array('module'=>$aModule), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
        }
} // endclass