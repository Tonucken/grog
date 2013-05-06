<?php
/** Instantierad CLydia för att kunna använda $this i underklasser
* @package LydiaCore */
class CObject {
	/** Members */
	protected $grog;
	protected $config;
	protected $request;
	protected $data;
	protected $db;
	protected $views;
	protected $session;
	protected $user;

	/** Constructor */
	protected function __construct($grog=null) {
		if(!$grog) {$grog = CGrog::Instance();}
		$this->grog 	= &$grog;
		$this->config 	= &$grog->config;
		$this->request 	= &$grog->request;
		$this->data 	= &$grog->data;
		$this->db 	= &$grog->db;
		$this->views 	= &$grog->views;
		$this->session 	= &$grog->session;
		$this->user 	= &$grog->user;
	}

	/** Wrapper för samma metoder som i CLydia */
	protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {$this->grog->RedirectTo($urlOrController, $method, $arguments);}
	protected function RedirectToController($method=null, $arguments=null) {$this->grog->RedirectToController($method, $arguments);}
	protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {$this->grog->RedirectToControllerMethod($controller, $method, $arguments);}
	protected function AddMessage($type, $message, $alternative=null) {return $this->grog->AddMessage($type, $message, $alternative);}
	protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {return $this->grog->CreateUrl($urlOrController, $method, $arguments);}
} // endclass
