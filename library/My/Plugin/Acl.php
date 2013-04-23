<?php
/**
 * Plugin pro kontrolu přístupových práv před přístupem na stránku.
 * @author Magda
 *
 */
class My_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
	
	private $acl;
	
	/**
	 * Vytvoří instanci pluginu.
	 * @param Zend_Acl $acl instance třídy Zend_Acl
	 */
	public function __construct(Zend_Acl $acl){
		$this->acl = $acl;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		if(Zend_Auth::getInstance()->hasIdentity()){
			$role = Zend_Auth::getInstance()->getIdentity()->role;
		}
		else{
			$role = 3;
		}
		
		$resource = $request->getControllerName();
		$action = $request->getActionName();
		
		if (!$this->acl->isAllowed($role, $resource, $action)){
			if($role == 3){
				$request->setControllerName('user')->setActionName('login');
			}
			else{
				$request->setControllerName('error')->setActionName('denied');
			}
		}
	}	
	
}