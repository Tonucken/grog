<?php
/** Bootstrapping. Setup och ladda kärnan
* @package GrogCore */

/** Autoladda klasser */
function autoload($aClassName) {
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = GROG_SITE_PATH . $classFile;
	$file2 = GROG_INSTALL_PATH . $classFile;
	if(is_file($file1)) {require_once($file1);}
	elseif(is_file($file2)) {require_once($file2);}
}
spl_autoload_register('autoload');

/** Tilldela default undantagshantering och log */
function exceptionHandler($e) {echo "Grog: Oförutsett undantag: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";}
set_exception_handler('exceptionHandler');

/** Helper. Inkludera fil och lagra i sträng. Gör $vars tillgängliga för den inkluderade filen */
function getIncludeContents($filename, $vars=array()) {
	if (is_file($filename)) {
		ob_start();
		extract($vars);
		include $filename;
		return ob_get_clean();
	}
	return false;
}

/** Helper. Tillämpa rätt character encoding för html_entites */
function htmlEnt($str, $flags = ENT_COMPAT) {return htmlentities($str, $flags, CGrog::Instance()->config['character_encoding']);}

/** Helper. Tidsformatering (behöver PHP5.3.) */
function formatDateTimeDiff($start, $startTimeZone=null, $end=null, $endTimeZone=null) {
	if(!($start instanceof DateTime)) {
		if($startTimeZone instanceof DateTimeZone) {$start = new DateTime($start, $startTimeZone);}
		else if(is_null($startTimeZone)) {$start = new DateTime($start);}
		else {$start = new DateTime($start, new DateTimeZone($startTimeZone));}
	}
	
	if($end === null) {$end = new DateTime();}
  
	if(!($end instanceof DateTime)) {
		if($endTimeZone instanceof DateTimeZone) {$end = new DateTime($end, $endTimeZone);}
		else if(is_null($endTimeZone)) {$end = new DateTime($end);}
		else {$end = new DateTime($end, new DateTimeZone($endTimeZone));}
	}
  
	$interval = $end->diff($start);
	$doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; 
  
	$format = array();
	if($interval->y !== 0) {$format[] = "%y ".$doPlural($interval->y, "år");}
	if($interval->m !== 0) {$format[] = "%m ".$doPlural($interval->m, "månad");}
	if($interval->d !== 0) {$format[] = "%d ".$doPlural($interval->d, "dag");}
	if($interval->h !== 0) {$format[] = "%h ".$doPlural($interval->h, "timma");}
	if($interval->i !== 0) {$format[] = "%i ".$doPlural($interval->i, "minut");}
	if(!count($format)) {return "mindre än en minut";}
	if($interval->s !== 0) {$format[] = "%s ".$doPlural($interval->s, "sekund");}
  	if($interval->s !== 0) {
  		if(!count($format)) {return "mindre än en minut";}
  		else {$format[] = "%s ".$doPlural($interval->s, "sekund");}
  	}
  
	if(count($format) > 1) {$format = array_shift($format)." och ".array_shift($format);}
	else {$format = array_pop($format);}
	return $interval->format($format);
}

/** Helper. Gör länkar i text klickbara */
function makeClickable($text) {
	return preg_replace_callback(
		'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
		create_function(
			'$matches',
			'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
			),
		$text
		);
}

/** Helper. Konvertera BBCode till HTML */
function bbcode2html($text) {
	$search = array(
		'/\[b\](.*?)\[\/b\]/is',
		'/\[i\](.*?)\[\/i\]/is',
		'/\[u\](.*?)\[\/u\]/is',
		'/\[img\](https?.*?)\[\/img\]/is',
		'/\[url\](https?.*?)\[\/url\]/is',
		'/\[url=(https?.*?)\](.*?)\[\/url\]/is'
		);
	$replace = array(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',
		'<img src="$1" />',
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>'
		);
	return preg_replace($search, $replace, $text);
} 
