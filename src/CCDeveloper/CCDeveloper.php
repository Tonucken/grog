<?php
/** Controller för utveckling och testande.
* @package GrogCore */
class CCDeveloper extends CObject implements IController {
	public function __construct() {parent::__construct();}
	public function Index() {$this->Menu();}
	
	/** Visa allt under CObject */
	public function DisplayObject() {	
		$this->Menu();
		$this->data['main'] .= <<<EOD
<h2>Innehållet i CDeveloper</h2>
<p>Här är controllerns innehåll inklusiver egenskaper från CObject som har tillgång till gemensamma resurser i CGrog.</p>
EOD;
		$this->data['main'] .= '<pre>' . htmlent(print_r($this, true)) . '</pre>';
	}
	
	/** Lista vilka länkar som stöds */
	public function Links() {	
		$this->Menu();
		$url = 'developer/links';
		$current = $this->request->CreateUrl($url);
		$this->request->cleanUrl = false;
		$this->request->querystringUrl = false;	
		$default = $this->request->CreateUrl($url);
		$this->request->cleanUrl = true;
		$clean = $this->request->CreateUrl($url);	
		$this->request->cleanUrl = false;
		$this->request->querystringUrl = true;	
		$querystring = $this->request->CreateUrl($url);
		$this->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Inställningar för olika varianter av url som alla ska leda till denna sidan. Ändras i site/config.php::urlhantering om så önskas</p>
<ul>
<li><a href='$current'>Aktuell inställning</a>
<li><a href='$default'>Default = config 0</a>
<li><a href='$clean'>Clean = config 1</a>
<li><a href='$querystring'>Querystring = config 2</a>
</ul>
EOD;
}

	/** Metod som visar menyn */
	private function Menu() {	
		$menu = array('developer', 'developer/index', 'developer/links', 'developer/display-object');
		$html = null;
		foreach($menu as $val) {$html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";}

		$this->data['title'] = "Controller för utvecklaren";
		$this->data['main'] = <<<EOD
<h1>Controller för utvecklaren</h1>
<p>Aktuella alternativ:</p>
<ul>
$html
</ul>
EOD;
	}
} // endclass