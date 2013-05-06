<?php
/** Class för att skapa och hantera formulär 
* @package GrogCore */
class CFormElement implements ArrayAccess {
	/** Properties */
	public $attributes;
	public $characterEncoding;
  
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		$this->attributes = $attributes;
		$this['name'] = $name;
		if(is_callable('CGrog::Instance()')) {
			$this->characterEncoding = CGrog::Instance()->config['character_encoding'];
		} else {$this->characterEncoding = 'UTF-8';}
	}
  
	/** Implementera ArrayAccess for this->attributes */
	public function offsetSet($offset, $value) {if (is_null($offset)) {$this->attributes[] = $value;} else {$this->attributes[$offset] = $value;}}
	public function offsetExists($offset) {return isset($this->attributes[$offset]);}
	public function offsetUnset($offset) {unset($this->attributes[$offset]);}
	public function offsetGet($offset) {return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;}

	/** Hämta HTML för element */
	public function GetHTML() {
		$id 		= isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
		$class 		= isset($this['class']) ? " {$this['class']}" : null;
		$validates 	= (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
		$class 		= (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
		$name 		= " name='{$this['name']}'";
		$label 		= isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
		$autofocus 	= isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;
		$readonly 	= isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;
		$type 		= isset($this['type']) ? " type='{$this['type']}'" : null;
		$onlyValue 	= isset($this['value']) ? htmlentities($this['value'], ENT_COMPAT, $this->characterEncoding) : null;
		$value 		= isset($this['value']) ? " value='{$onlyValue}'" : null;
		
		$messages 	= null;
		if(isset($this['validation-messages'])) {
			$message = null;
			foreach($this['validation-messages'] as $val) {$message .= "<li>{$val}</li>\n";}
			$messages = "<ul class='validation-message'>\n{$message}</ul>\n";
		}
		
		if($type && $this['type'] == 'submit') {return "<span><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} /></span>\n";}
		else if($type && $this['type'] == 'textarea') {return "<p><label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$onlyValue}</textarea></p>\n";}
		else if($type && $this['type'] == 'hidden') {return "<input id='$id'{$type}{$class}{$name}{$value} />\n";}
		else {return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";}
	}

	/** Validera formuläret */
	public function Validate($rules) {
		$tests = array(
			'fail' => array('message' => 'Kommer aldrig gå igenom.', 'test' => 'return false;'),
			'pass' => array('message' => 'Kommer alltid gå igenom.', 'test' => 'return true;'),
			'not_empty' => array('message' => 'Får ej vara tomt.', 'test' => 'return $value != "";'),
			);
		$pass = true;
		$messages = array();
		$value = $this['value'];
		foreach($rules as $key => $val) {
			$rule = is_numeric($key) ? $val : $key;
			if(!isset($tests[$rule])) throw new Exception('Validering av element i formuläret misslyckades. Sådan regel för validering saknas.');
			if(eval($tests[$rule]['test']) === false) {
				$messages[] = $tests[$rule]['message'];
				$pass = false;
			}
		}
		if(!empty($messages)) $this['validation-messages'] = $messages;
		return $pass;
	}

	/** Använd elementets namn som etikett, om inte annat angivits */
	public function UseNameAsDefaultLabel() {if(!isset($this['label'])) {$this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';}}

	/** Använd elementets namn som innehåll, om inte annat angivits */
	public function UseNameAsDefaultValue() {if(!isset($this['value'])) {$this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));}}
} // endclass


class CFormElementText extends CFormElement {
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'text';
		$this->UseNameAsDefaultLabel();
	}
} // endclass


class CFormElementTextarea extends CFormElement {
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'textarea';
		$this->UseNameAsDefaultLabel();
	}
} // endclass


class CFormElementHidden extends CFormElement {
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'hidden';
	}
} // endclass


class CFormElementPassword extends CFormElement {
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'password';
		$this->UseNameAsDefaultLabel();
	}
} // endclass


class CFormElementSubmit extends CFormElement {
	/** Constructor */
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'submit';
		$this->UseNameAsDefaultValue();
	}
} // endclass


class CForm implements ArrayAccess {
	/** Properties */
	public $form; 
	public $elements; 
  
	/** Constructor */
	public function __construct($form=array(), $elements=array()) {
		$this->form = $form;
		$this->elements = $elements;
	}
	
	/** Implementerar ArrayAccess för this->elements */
	public function offsetSet($offset, $value) {if (is_null($offset)) {$this->elements[] = $value;} else {$this->elements[$offset] = $value;}}
	public function offsetExists($offset) {return isset($this->elements[$offset]);}
	public function offsetUnset($offset) {unset($this->elements[$offset]);}
	public function offsetGet($offset) {return isset($this->elements[$offset]) ? $this->elements[$offset] : null;}

	/** Lägg till formulär-element */
	public function AddElement($element) {
		$this[$element['name']] = $element;
		return $this;
	}
  
	/** Validering till formulär-element */
	public function SetValidation($element, $rules) {
		$this[$element]['validation'] = $rules;
		return $this;
	}
  
	/** Presentera HTML för formuläret */
	public function GetHTML($attributes=null) {
		if(is_array($attributes)) {$this->form = array_merge($this->form, $attributes);}
		$id 	= isset($this->form['id']) ? " id='{$this->form['id']}'" : null;
		$class 	= isset($this->form['class']) ? " class='{$this->form['class']}'" : null;
		$name 	= isset($this->form['name']) ? " name='{$this->form['name']}'" : null;
		$action = isset($this->form['action']) ? " action='{$this->form['action']}'" : null;
		$method = " method='post'";

		if(isset($attributes['start']) && $attributes['start']) {return "<form{$id}{$class}{$name}{$action}{$method}>";}
    
		$elements = $this->GetHTMLForElements();
		$html = <<< EOD
<form{$id}{$class}{$name}{$action}{$method}>
<fieldset>
{$elements}
</fieldset>
</form>
EOD;
 		return $html;
 	}
 
 	/** Presentera HTML för elementen */
 	public function GetHTMLForElements() {
 		$html = null;
 		$buttonbar = null;
 		foreach($this->elements as $element) {
 			if(!$buttonbar && $element['type'] == 'submit') {
 				$buttonbar = true;
 				$html .= '<p>';
 			} else if($buttonbar && $element['type'] != 'submit') {
 				$buttonbar = false;
 				$html .= '</p>\n';
 			}
 			$html .= $element->GetHTML();
 		}
 		return $html;
 	}
  
 	/** Kontrollera om formulär skickats, validera och anropa callbacks */
 	public function Check() {
 		$validates = null;
 		$callbackStatus = null;
 		$values = array();
 		if($_SERVER['REQUEST_METHOD'] == 'POST') {
 			unset($_SESSION['form-failed']);
 			$validates = true;
 			foreach($this->elements as $element) {
 				if(isset($_POST[$element['name']])) {
 					$values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
 					if(isset($element['validation'])) {
 						$element['validation-pass'] = $element->Validate($element['validation']);
 						if($element['validation-pass'] === false) {
 							$values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
 							$validates = false;
 						}
 					}
 					if(isset($element['callback']) && $validates) {
 						if(isset($element['callback-args'])) {if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) {$callbackStatus = false;}} 
 						else {if(call_user_func($element['callback'], $this) === false) {$callbackStatus = false;}}
 					}
 				}
 			}
 		} else if(isset($_SESSION['form-failed'])) {
 			foreach($_SESSION['form-failed'] as $key => $val) {
 				$this[$key]['value'] = $val['value'];
 				if(isset($val['validation-messages'])) {
 					$this[$key]['validation-messages'] = $val['validation-messages'];
 					$this[$key]['validation-pass'] = false;
 				}
 			}
 			unset($_SESSION['form-failed']);
 		}
 		if($validates === false || $callbackStatus === false) {$_SESSION['form-failed'] = $values;}
 		if($callbackStatus === false)
 			return false;
 		else
 		return $validates;
 	}
} // endclass
