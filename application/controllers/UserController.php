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
        		$existingUser = $mapper->findByUsername($form->getValue('email'), new Application_Model_User());
        		if(null === $existingUser){
        			$salt = $this->generateSalt();
        			$password = $this->encrypt($user->password, $salt);
        			$salt = base64_encode($salt);
        			$user->password = $password;
        			$user->salt = $salt;
        			//TODO přepsat roli
        			$user->role = 1;
        			$mapper->save($user);
        			//TODO přihlásit uživatele
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
        // action body
    }

    public function logoutAction()
    {
        // action body
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


}







