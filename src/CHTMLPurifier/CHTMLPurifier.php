<?php
/** HTMLPurifier by Edward Z. Yang, http://htmlpurifier.org/
* @package GrogCore */
class CHTMLPurifier {
	/** Properties */
	public static $instance = null;

	/** Rena. Instansiera HTMLPurifier om det inte redan finns */
	public static function Purify($text) {
		if(!self::$instance) {
			require_once(__DIR__.'/htmlpurifier-4.5.0-standalone/HTMLPurifier.standalone.php');
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Cache.DefinitionImpl', null);
			self::$instance = new HTMLPurifier($config);
		}
		return self::$instance->purify($text);
	}
} // endclass
