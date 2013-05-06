<?php
/** Controller för att testa teman.
* @package GrogCore */
class CCTheme extends CObject implements IController {
	public function __construct() {
		parent::__construct();
		$this->views->AddStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/grogstyle/grid_12_60_20.png) repeat-y center top;}');
	}

	/** Visa vad som kan göras */
	public function Index() {		    // Hämta lista över alla tillgängliga metoder
		$rc = new ReflectionClass(__CLASS__);
		$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
		$items = array();
		foreach($methods as $method) {
			if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
				$items[] = $this->request->controller . '/' . mb_strtolower($method->name);
			}
		}

		$this->views->SetTitle('Tema')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                	'theme_name' => $this->config['theme']['name'],
                	'methods' => $items,
                	));
        }

        /** Lägg innehåll i valda grid-regioner från 'grog/themes/grid/style.less' */
        public function SomeRegions() {
        	$this->views->SetTitle('Temat visar innehåll för vissa regioner')
        	->AddString('Detta är primary regionen', array(), 'primary');
                
        	if(func_num_args()) {
        		foreach(func_get_args() as $val) {
        			$this->views->AddString("Detta är region: $val", array(), $val)
        			->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
        		}
        	}
        }

        /** Lägg innehåll i alla befintliga regioner */
        public function AllRegions() {
        	$this->views->SetTitle('Temat visar innehåll för alla regioner');
        	foreach($this->config['theme']['regions'] as $val) {
        		$this->views->AddString("Detta är region: $val", array(), $val)
        		->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
        	}
        }

        /** Visa text-exempel i h1-h6 och inlineformaterade paragrafer */
        public function H1H6() {
        	$this->views->SetTitle('Temat testar rubriker och paragrafer')
                ->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
        }
} // endclass 
