<?php
/** Kärnklassen för hela Grog 
* @package GrogCore */
class CGrog implements ISingleton {
	/** Members */
	private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $user;
	public $timer = array();

	/** Constructor */
	protected function __construct() {
		$this->timer['first'] = microtime(true);
		$grog = &$this;
		require(GROG_SITE_PATH.'/config.php');
		
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();

		date_default_timezone_set('UTC');

		if(isset($this->config['database'][0]['dsn'])) {$this->db = new CDatabase($this->config['database'][0]['dsn']);}
  
		$this->views = new CViewContainer();
		$this->user = new CMUser($this);
	}
  
	/** Singleton. Hämta senaste instansiering av skapat objekt, eller skapa nytt */
	public static function Instance() {
		if(self::$instance == null) {self::$instance = new CGrog();}
		return self::$instance;
	}

	/** Frontcontroller, check url and route to controllers. */
	public function FrontControllerRoute() {
		$this->request 	= new CRequest($this->config['url_type']);
		$this->request->Init($this->config['base_url'], $this->config['routing']);
		$controller 	= $this->request->controller;
		$method 	= $this->request->method;
		$arguments 	= $this->request->arguments;
    
		$controllerExists 	= isset($this->config['controllers'][$controller]);
		$controllerEnabled 	= false;
		$className		= false;
		$classExists 		= false;

		if($controllerExists) {
			$controllerEnabled	= ($this->config['controllers'][$controller]['enabled'] == true);
			$className		= $this->config['controllers'][$controller]['class'];
			$classExists 		= class_exists($className);
		}
		
		if($controllerExists && $controllerEnabled && $classExists) {
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) {
				$formattedMethod = str_replace(array('_', '-'), '', $method);
				if($rc->hasMethod($formattedMethod)) {
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($formattedMethod);
					if($methodObj->isPublic()) {$methodObj->invokeArgs($controllerObj, $arguments);}
					else {die("404. " . get_class() . ' error: Controllerns metod är inte öppen.');}
				} else {die("404. " . get_class() . ' error: Controllern innehåller ingen metod.');}
			} else {die('404. ' . get_class() . ' error: Controllern implementerar inte IController.');}
		} else {die('404. Sidan hittas inte.');}
	}
    
	/** ThemeEngineRender, renderar requestens svar till HTML, eller annat språk */
	public function ThemeEngineRender() {				// Spara till session före output
		$this->session->StoreInSession();
		
		if(!isset($this->config['theme'])) { return; }		// Är tema aktiverat?
		
		$themePath 	= GROG_INSTALL_PATH . '/' . $this->config['theme']['path'];		// Hämta temats path och settings 
		$themeUrl	= $this->request->base_url . $this->config['theme']['path'];		// Kontrollera i 'grog/site/' först
		
		$parentPath = null;					// Finns det parent theme?
		$parentUrl = null;
		if(isset($this->config['theme']['parent'])) {
			$parentPath 	= GROG_INSTALL_PATH . '/' . $this->config['theme']['parent'];
			$parentUrl	= $this->request->base_url . $this->config['theme']['parent'];
		}
		
		$this->data['stylesheet'] = $this->config['theme']['stylesheet'];		// Inkludera stylesheetets namn till $grog->data array
		
		$this->themeUrl = $themeUrl;			// Gör temats url tillgänglig för $grog
		$this->themeParentUrl = $parentUrl;
		
		if(is_array($this->config['theme']['menu_to_region'])) {foreach($this->config['theme']['menu_to_region'] as $key => $val) {$this->views->AddString($this->DrawMenu($key), null, $val);}}	// Koppla meny till region, om det definerats
		
		$grog = &$this;							// Inkludera både den globala functions.php och temats lokala functions.php
		include(GROG_INSTALL_PATH . '/themes/functions.php');		// Först den globala 
		if($parentPath) {if(is_file("{$parentPath}/functions.php")) {include "{$parentPath}/functions.php";}}	// Sedan parent 
		if(is_file("{$themePath}/functions.php")) {include "{$themePath}/functions.php";}	// Och sist den lokala
		
		extract($this->data);
		extract($this->views->GetData());		// Extrahera $grog->data till variabler för template filen
		if(isset($this->config['theme']['data'])) {extract($this->config['theme']['data']);}
		
		// Kör template filen
		$templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		if(is_file("{$themePath}/{$templateFile}")) {include("{$themePath}/{$templateFile}");} 
		else if(is_file("{$parentPath}/{$templateFile}")) {include("{$parentPath}/{$templateFile}");}
		else {throw new Exception('Ingen sådan template fil finns.');}
	}

	/** Dirigera om till en annan url och lagra sessionen */
	public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
		if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {$this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());}
		if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {$this->session->SetFlash('database_queries', $this->db->GetQueries());}
		if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {$this->session->SetFlash('timer', $this->timer);}
		$this->session->StoreInSession();
		header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
		exit;
	}

	/** Dirigera om till metod i nuvarande controller */
	public function RedirectToController($method=null, $arguments=null) {$this->RedirectTo($this->request->controller, $method, $arguments);}

	/** Dirigera om till controller och metod */
	public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
		$controller = is_null($controller) ? $this->request->controller : null;
		$method = is_null($method) ? $this->request->method : null;	
		$this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
	}
	
	/** Spara meddelande i sessionen med $this->session->AddMessage() */
  	public function AddMessage($type, $message, $alternative=null) {
  		if($type === false) {
  			$type = 'error';
  			$message = $alternative;
  		} else if($type === true) {$type = 'success';}
  		$this->session->AddMessage($type, $message);
  	}

  	/** Skapa url */
  	public function CreateUrl($urlOrController=null, $method=null, $arguments=null) {return $this->request->CreateUrl($urlOrController, $method, $arguments);}

  	/** HTML för menyn definierad i 'grog/site/config.php' $grog->config['menus'] */
  	public function DrawMenu($menu) {
  		$items = null;
  		if(isset($this->config['menus'][$menu])) {
  			foreach($this->config['menus'][$menu] as $val) {
  				$selected = null;
  				if($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) {$selected = " class='selected'";}
  				$items .= "<li><a {$selected} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
  			}
  		} else {throw new Exception('Ingen sådan meny finns.');}
  		return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
  	}
} // endclass
