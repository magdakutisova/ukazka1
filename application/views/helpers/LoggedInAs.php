<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract{
	
	public function loggedInAs(){
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()){
			$username = $auth->getIdentity()->email;
			
			$logoutUrl = $this->view->url(array(), 'userLogout');
			
			return '<p class="no-margin"><span class="bold">Přihlášen: </span>' . $username
				. '</p><p class="no-margin"><a href="' . $logoutUrl
				. '">Odhlásit se</a></p>';
		}
		
		$loginUrl = $this->view->url(array(), 'userLogin');
		$registerUrl = $this->view->url(array(), 'userRegister');
		return '<a href="' . $loginUrl . '">Přihlásit se</a><br/>
				<a href="' . $registerUrl . '">Registrovat se</a>';
	}
	
}