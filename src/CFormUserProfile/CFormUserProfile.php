<?php
/** Formulär för att redigera användarprofilen 
* @package GrogCore */
class CFormUserProfile extends CForm {
	/** Constructor */
	public function __construct($object, $user) {
		parent::__construct();
		$this->AddElement(new CFormElementText('acronym', array('label'=>'Användarnamn:', 'readonly'=>true, 'value'=>$user['acronym'])))
		->AddElement(new CFormElementPassword('password', array('label'=>'Lösenord:')))
		->AddElement(new CFormElementPassword('password1', array('label'=>'Lösenord igen:')))
		->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))))
		->AddElement(new CFormElementText('name', array('label'=>'Namn:', 'value'=>$user['name'], 'required'=>true)))
		->AddElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
		->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoProfileSave'))));
         
		$this->SetValidation('name', array('not_empty'))
		->SetValidation('email', array('not_empty'));
	}
} // endclass
