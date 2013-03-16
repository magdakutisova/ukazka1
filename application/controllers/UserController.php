<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function registerAction()
    {
        $form = new Application_Form_Register();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost()){
        	if($form->isValid($this->getRequest()->getPost())){
        		$user = new Application_Model_User($form->getValues());
        		$mapper = new Application_Model_UserMapper();
        		$existingUser = $mapper->findByEmail($form->getValue('email'), new Application_Model_User());
        		if(null === $existingUser){
        			$salt = $this->generateSalt();
        			$password = $this->encrypt($user->password, $salt);
        			$salt = base64_encode($salt);
        			$user->password = $password;
        			$user->salt = $salt;
        			$user->role = 2;
        			$mapper->save($user);
        			$this->process(array(
        					'email' => $user->email,
        					'password' => $form->getValue('password'),
        					));
        			$this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        		}
        		else{
        			$this->_helper->FlashMessenger('Uživatel s tímto emailem již existuje.');
        			$this->_helper->redirector->gotoRoute(array(), 'userRegister');
        		}
        	}
        }
    }

    public function loginAction()
    {
        $form = new Application_Form_Login();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost()){
        	if($form->isValid($this->getRequest()->getPost())){
        		if($this->process($form->getValues())){
        			$this->_helper->FlashMessenger('Přihlášení bylo úspěšné.');
        			$this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        		}
        		else{
        			$this->_helper->FlashMessenger('Chybné uživatelské jméno nebo heslo.');
        			$this->_helper->redirector->gotoRoute(array(), 'userLogin');
        		}
        	}
        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
    }
    
    private function generateSalt()
    {
    	$salt = mcrypt_create_iv ( 64 );
    	return $salt;
    }
    
    private function encrypt($password, $salt)
    {
    	$password = hash ( 'sha256', $salt . $password );
    	return $password;
    }
    
    private function process($values){
    	$mapper = new Application_Model_UserMapper();
    	$user = $mapper->findByEmail($values['email'], new Application_Model_User());
    	if(!$user){
    		$this->_helper->FlashMessenger('Uživatel s emailem "' . $values['email'] . '" neexistuje.');
    		$this->_helper->redirector->gotoRoute(array(), 'userLogin');
    	}
    	else{
    		$password = $values['password'];
    		$salt = base64_decode($user->salt);
    		$password = $this->encrypt($password, $salt);
    		
    		$adapter = $this->getAuthAdapter();
    		$adapter->setIdentity($values['email']);
    		$adapter->setCredential($password);
    		
    		$auth = Zend_Auth::getInstance();
    		$result = $auth->authenticate($adapter);
    		
    		if($result->isValid()){
    			$loggedUser = $adapter->getResultRowObject();
    			$auth->getStorage()->write($loggedUser);
    			return true;
    		}
    		return false;
    	}
    }
    
	private function getAuthAdapter(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		
		$authAdapter->setTableName('user')
			->setIdentityColumn('email')
			->setCredentialColumn('password');
		
		return $authAdapter;
	}

}







