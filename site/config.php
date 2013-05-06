<?php
ignore_user_abort(true);		
set_time_limit(40);			
ini_set("memory_limit", "8M");


/** Felhantering */
error_reporting(-1);
ini_set('display_errors', 1);

/* Alternatvi felhantering - spara till logfil istället för skriva ut
ini_set("log_errors", 1);
ini_set("error_log", "/felmeddelanden/error.log");
error_log( "Hello, errors!" );
*/

/** Sessionsnamn */
$grog->config['session_name']	= preg_replace('/[:\.\/-_]/', '', __DIR__);
$grog->config['session_key'] 	= 'grog';

/** URL hantering */
$grog->config['url_type'] 	= 1;
$grog->config['base_url'] 	= null;
$grog->config['routing']	= array('home' => array('enabled' => true, 'url' => 'index/index'),);

/** Databas path */
$grog->config['database'][0]['dsn'] = 'sqlite:' . GROG_SITE_PATH . '/data/.ht.sqlite';

/** Tidszon, språk, teckenkodning */
$grog->config['timezone'] = 'Europe/Stockholm';
$grog->config['language'] = 'en';
$grog->config['character_encoding'] = 'UTF-8';

/** Users */
$grog->config['create_new_users'] = true;      
$grog->config['hashing_algorithm'] = 'sha1salt';

/** Debug information */
$grog->config['debug']['grog'] = false;
$grog->config['debug']['session'] = false;
$grog->config['debug']['timer'] = true;
$grog->config['debug']['db-num-queries'] = true;
$grog->config['debug']['db-queries'] = true;

/** Tillgängliga Controllers */
$grog->config['controllers'] = array(
	'index' 	=> array('enabled' => true,'class' 	=> 'CCIndex'),
	'blog' 		=> array('enabled' => true,'class' 	=> 'CCBlog'),
	'guestbook' 	=> array('enabled' => true,'class' 	=> 'CCGuestbook'),
	'my' 		=> array('enabled' => true,'class' 	=> 'CCMycontroller'),
	'content' 	=> array('enabled' => true,'class' 	=> 'CCContent'),
	'user' 		=> array('enabled' => true,'class' 	=> 'CCUser'),
	'acp' 		=> array('enabled' => true,'class' 	=> 'CCAdminControlPanel'),
	'module' 	=> array('enabled' => true,'class' 	=> 'CCModules'),
	'developer' 	=> array('enabled' => true,'class' 	=> 'CCDeveloper'),
	'theme' 	=> array('enabled' => true,'class' 	=> 'CCTheme'),
	'page' 		=> array('enabled' => true,'class' 	=> 'CCPage'),
	);

/** Hårdkodade menyer */
$grog->config['menus'] = array(
	'navbar' => array(
		'home' 		=> array('label'=>'Home', 	'url'=>'home'),
		'modules' 	=> array('label'=>'Modules', 	'url'=>'module'),
		'content' 	=> array('label'=>'Content', 	'url'=>'content'),
		'guestbook' 	=> array('label'=>'Guestbook', 	'url'=>'guestbook'),
		'blog' 		=> array('label'=>'Blog', 	'url'=>'blog'),
		),
	'my-navbar' => array(
		'home' 		=> array('label'=>'Om mig', 	'url'=>'my'),
		'blog' 		=> array('label'=>'Min Grog', 	'url'=>'my/blog'),
		'guestbook' 	=> array('label'=>'GrogBook', 	'url'=>'my/guestbook'),
		),
	);

/** Temasettings. Koppla menyerna till regioner. Footern består av en hårdkodad del här som hamnar överst, och en datadel som hittas i themes/index.tpl.php */
$grog->config['theme'] = array(
	'path' 		=> 'site/themes/userstheme',
	'parent' 	=> 'themes/grogstyle',
	'stylesheet' 	=> 'style.css',
	'template_file' => 'index.tpl.php',
	'regions' 	=> array(
		'navbar', 'flash','featured-first','featured-middle','featured-last',
		'primary','sidebar','triptych-first','triptych-middle','triptych-last',
		'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
		'footer',
		),
	'menu_to_region' => array('my-navbar'=>'navbar'),
	'data' => array(
		'header' 	=> 'Grog',
		'slogan' 	=> '<u>G</u>ringos <u>R</u>amverk <u>O</u>m <u>G</u>rundläggande model/view/controller',
		'favicon' 	=> 'grog.png',
		'logo' 		=> 'grog.png',
		'logo_width' 	=> 80,
		'logo_height' 	=> 80,
		'footer' 	=> '<span style="text-align:center"><h2>Grog</h2> <h3>by <a href="mailto:tony.pinberg@gmail.com">Tony Pinberg</a> &copy 2013</h3></span><br>',
		),
	);
