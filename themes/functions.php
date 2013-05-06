<?php
/** Definierade funktioner till teman. Denna filen inkluderas FÖRE temats egen functions.php */
 
/** Debug */
function get_debug() {$grog = CGrog::Instance();
	if(empty($grog->config['debug'])) {return;}
	
	$html = null;	  // Get the debug output
	if(isset($grog->config['debug']['db-num-queries']) && $grog->config['debug']['db-num-queries'] && isset($grog->db)) {
		$flash = $grog->session->GetFlash('database_numQueries');
		$flash = $flash ? "$flash + " : null;
		$html .= "Databasen gjorde följande ($flash" . $grog->db->GetNumQueries() . " st.) sökningar:";
	}
	if(isset($grog->config['debug']['db-queries']) && $grog->config['debug']['db-queries'] && isset($grog->db)) {
		$flash = $grog->session->GetFlash('database_queries');
		$queries = $grog->db->GetQueries();
		if($flash) {$queries = array_merge($flash, $queries);}
		$html .= '<br>' . implode($queries);
	}
	if(isset($grog->config['debug']['timer']) && $grog->config['debug']['timer']) {$html .= "<br>Sidan laddades på " . round(microtime(true) - $grog->timer['first'], 5)*1000 . " msekunder.";}
	if(isset($grog->config['debug']['grog']) && $grog->config['debug']['grog']) {$html .= "<hr><h3>Debuginformation</h3><p>Innehållet i CGrog:</p><pre>" . htmlent(print_r($grog, true)) . "</pre>";}
	if(isset($grog->config['debug']['session']) && $grog->config['debug']['session']) {
		$html .= "<hr><h3>SESSION</h3><p>Innehållet i CGrog->session:</p><pre>" . htmlent(print_r($grog->session, true)) . "</pre>";
		$html .= "<p>Innehållet i \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
	}
	return $html;
}

/** Hämta meddelanden lagrat i flash-sessionen */
function get_messages_from_session() {
	$messages = CGrog::Instance()->session->GetMessages();
	$html = null;
	if(!empty($messages)) {
		foreach($messages as $val) {
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='$class'>{$val['message']}</div>\n";
		}
	}
	return $html;
}


/** Login meny */
function login_menu() {
	$grog = CGrog::Instance();
	if($grog->user['isAuthenticated']) {
		$items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $grog->user['acronym'] . "</a> ";
		if($grog->user['hasRoleAdministrator']) {$items .= "<a href='" . create_url('acp') . "'>acp</a> ";}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	} else {$items = "<a href='" . create_url('user/login') . "'>login</a> ";}
  return "<nav id='login-menu'>$items</nav>";
}

/** Gravatar */
function get_gravatar($size=null) {return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CGrog::Instance()->user['email']))) . '.jpg?r=pg&amp;d=wavatar&amp;' . ($size ? "s=$size" : null);}

/** Escape data för att undvika browsermanipulering */
function esc($str) {return htmlEnt($str);}

/** Filtrera data. Använder CMContent::Filter() */
function filter_data($data, $filter) {return CMContent::Filter($data, $filter);}

/** Display diff of time between now and a datetime. */
function time_diff($start) {return formatDateTimeDiff($start);}

/** Prepend the base_url */
function base_url($url=null) {return CGrog::Instance()->request->base_url . trim($url, '/');}

/** Create a url to an internal resource */
function create_url($urlOrController=null, $method=null, $arguments=null) {return CGrog::Instance()->CreateUrl($urlOrController, $method, $arguments);}

/** Prepend the theme_url, which is the url to the current theme directory */
function theme_url($url) {return create_url(CGrog::Instance()->themeUrl . "/{$url}");}

/** Prepend the theme_parent_url, which is the url to the parent theme directory */
function theme_parent_url($url) {return create_url(CGrog::Instance()->themeParentUrl . "/{$url}");}

/** Return the current url */
function current_url() {return CGrog::Instance()->request->current_url;}

/** Render all views */
function render_views($region='default') {return CGrog::Instance()->views->Render($region);}

/** Check if region has views. Accepts variable amount of arguments as regions */
function region_has_content($region='default' /*...*/) {return CGrog::Instance()->views->RegionHasView(func_get_args());}
