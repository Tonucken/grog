<?php
/** Controller för användare, login, användarprofiler
* @package GrogCore */
class CCUser extends CObject implements IController {
	public function __construct() {parent::__construct();}

	/** Visa användarens profil */
	public function Index() {
		$this->views->SetTitle('AnvändarController')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                	'is_authenticated'=>$this->user['isAuthenticated'],
                	'user'=>$this->user,
                	));
        }
  
        /** Visa och redigera profil */
        public function Profile() {
        	$form = new CFormUserProfile($this, $this->user);
        	if($form->Check() === false) {
        		$this->AddMessage('notice', 'Något/några fält kunde inte valideras. Formuläret har inte skickats.');
        		$this->RedirectToController('profile');
        	}

        	$this->views->SetTitle('AnvändarProfil')
                ->AddInclude(__DIR__ . '/profile.tpl.php', array(
                	'is_authenticated'=>$this->user['isAuthenticated'],
                	'user'=>$this->user,
                	'profile_form'=>$form->GetHTML(),
                	));
        }
  
        /** Ändra lösenord */
        public function DoChangePassword($form) {
        	if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
        		$this->AddMessage('error', 'Lösenordet stämmer inte.');
        	} else {
        		$ret = $this->user->ChangePassword($form['password']['value']);
        		$this->AddMessage($ret, 'Nytt lösenord sparat.', 'Lösenordet har INTE ändrats.');
        	}
        	$this->RedirectToController('profile');
        }
  
        /** Spara uppdateringar i profilen */
        public function DoProfileSave($form) {
        	$this->user['name'] = $form['name']['value'];
        	$this->user['email'] = $form['email']['value'];
        	$ret = $this->user->Save();
        	$this->AddMessage($ret, 'Profil sparad.', 'Profil INTE sparad.');
        	$this->RedirectToController('profile');
        }
  
        /** Autenticera och logga in användare */
        public function Login() {
        	$form = new CFormUserLogin($this);
        	if($form->Check() === false) {
        		$this->AddMessage('notice', 'Du måste ange användarnamn och lösenord.');
        		$this->RedirectToController('login');
        	}
        	$this->views->SetTitle('Login')
                ->AddInclude(__DIR__ . '/login.tpl.php', array(
                	'login_form' => $form,
                	'allow_create_user' => CGrog::Instance()->config['create_new_users'],
                	'create_user_url' => $this->CreateUrl(null, 'create'),
                	));
        }
  
        /** Genomför login som callback på skickat formulär */
        public function DoLogin($form) {
        	if($this->user->Login($form['acronym']['value'], $form['password']['value'])) {
        		$this->AddMessage('success', "Välkommen {$this->user['name']}.");
        		$this->RedirectToController('profile');
        	} else {
        		$this->AddMessage('notice', "Kunde inte logga in. Användaren finns inte, eller lösenordet stämmer inte.");
        		$this->RedirectToController('login');
        	}
        }
  
        /** Logga ut */
        public function Logout() {
        	$this->user->Logout();
        	$this->RedirectToController('login');
        }
  
        /** Skapa ny användare */
        public function Create() {
        	$form = new CFormUserCreate($this);
        	if($form->Check() === false) {
        		$this->AddMessage('notice', 'Du måste fylla i alla fält.');
        		$this->RedirectToController('Create');
        	}
        	$this->views->SetTitle('Skapa användare')
                ->AddInclude(__DIR__ . '/create.tpl.php', array('form' => $form->GetHTML()));
        }
  
        /** Skapa användare som callback av skickat formulär */
        public function DoCreate($form) {
        	if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
        		$this->AddMessage('error', 'Lösenordet stämmer inte, eller är tomt.');
        		$this->RedirectToController('create');
        	} else if($this->user->Create($form['acronym']['value'],
        		$form['password']['value'],
                        $form['name']['value'],
                        $form['email']['value']
                        )) 
                {
                	$this->AddMessage('success', "Välkommen {$this->user['name']}. Du har skapat nytt konto.");
                	$this->user->Login($form['acronym']['value'], $form['password']['value']);
                	$this->RedirectToController('profile');
                } else {
                	$this->AddMessage('notice', "Misslyckades att skapa konto.");
                	$this->RedirectToController('create');
                }
        }
  
        /** Initiera användardatabasen */
        public function Init() {
        	$this->user->Init();
        	$this->RedirectToController();
        }
} // endclass 
