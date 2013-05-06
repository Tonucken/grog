<?php
//
// PHASE: BOOTSTRAP
//
define('GROG_INSTALL_PATH', dirname(__FILE__));
define('GROG_SITE_PATH', GROG_INSTALL_PATH . '/site');

require(GROG_INSTALL_PATH.'/src/bootstrap.php');

$grog = CGrog::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$grog->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER
//
$grog->ThemeEngineRender();