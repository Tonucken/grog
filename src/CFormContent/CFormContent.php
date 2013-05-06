<?php
/** Formulär för innehåll 
* @package GrogCore */
class CFormContent extends CForm {
	private $content;

	/** Constructor */
	public function __construct($content) {
		parent::__construct();
		$this->content = $content;
		$save = isset($content['id']) ? 'save' : 'create';
		$this->AddElement(new CFormElementHidden('id', array('value'=>$content['id'])))
		->AddElement(new CFormElementText('title', array('label'=>'Titel:', 'value'=>$content['title'])))
		->AddElement(new CFormElementText('key', array('label'=>'Nyckelord:', 'value'=>$content['key'])))
		->AddElement(new CFormElementTextarea('data', array('label'=>'Innehåll:', 'value'=>$content['data'])))
		->AddElement(new CFormElementText('type', array('label'=>'Typ (page, post):', 'value'=>$content['type'])))
		->AddElement(new CFormElementText('filter', array('label'=>'Filter (plain, BBCode, HTMLPurify):', 'value'=>$content['filter'])))
		->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))))
		->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($content))));

		$this->SetValidation('title', array('not_empty'))
		->SetValidation('key', array('not_empty'));
	}
  
	/** Callback för att spara formulär till databas */
	public function DoSave($form, $content) {
		$content['id'] 		= $form['id']['value'];
		$content['title'] 	= $form['title']['value'];
		$content['key'] 	= $form['key']['value'];
		$content['data'] 	= $form['data']['value'];
		$content['type'] 	= $form['Typ']['value'];
		$content['filter'] 	= $form['filter']['value'];
		return $content->Save();
	}
    
	/** Callback för att radera innehåll */
	public function DoDelete($form, $content) {
		$content['id'] = $form['id']['value'];
		$content->Delete();
		CGrog::Instance()->RedirectTo('content');
	}
} // endclass
