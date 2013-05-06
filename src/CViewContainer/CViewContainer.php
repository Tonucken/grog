<?php
/** Innehåller diverse vyer 
* @package GrogCore */

class CViewContainer {
	/** Members */
	private $data = array();
	private $views = array();

	/** Constructor */
	public function __construct() { ; }

	/** Getters */
	public function GetData() {return $this->data;}
  
  	/** Tilldela titel för aktuell sida */
  	public function SetTitle($value) {return $this->SetVariable('title', $value);}

  	/** Variabler som behöver vara tillgängliga för tema-motorn */
  	public function SetVariable($key, $value) {
  		$this->data[$key] = $value;
  		return $this;
  	}
  
  	/** inline style */
  	public function AddStyle($value) {
  		if(isset($this->data['inline_style'])) {$this->data['inline_style'] .= $value;}
  		else {$this->data['inline_style'] = $value;}
  		return $this;
  	}
  
  	/** Lägg till en vy som fil som kan inkluderas */
  	public function AddInclude($file, $variables=array(), $region='default') {
  		$this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
  		return $this;
  	}
  
  	/** Lägg till text och valfria variabler */
  	public function AddString($string, $variables=array(), $region='default') {
  		$this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
  		return $this;
  	}
          
  	/** Kontrollera om det finns vyer för specifika regioner */
  	public function RegionHasView($region) {
  		if(is_array($region)) {
  			foreach($region as $val) {if(isset($this->views[$val])) {return true;}}
  			return false;
  		} else {return(isset($this->views[$region]));}
  	}
   
  	/** Rendera vyer enligt sin typ */
  	public function Render($region='default') {
  		if(!isset($this->views[$region])) return;
  		foreach($this->views[$region] as $view) {
  			switch($view['type']) {
  			case 'include': if(isset($view['variables'])) extract($view['variables']); include($view['file']); break;
  			case 'string': if(isset($view['variables'])) extract($view['variables']); echo $view['string']; break;
  			}
  		}
  	}
} // endclass
