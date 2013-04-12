<?php
class My_Controller_Helper_Acl extends Zend_Acl{
	
	public function __construct(){
		$this->add(new Zend_Acl_Resource('book'));
		$this->add(new Zend_Acl_Resource('user'));
		$this->add(new Zend_Acl_Resource('error'));
		
		$guest = 3;
		$user = 2;
		$admin = 1;
		
		$this->addRole(new Zend_Acl_Role($guest));
		$this->addRole(new Zend_Acl_Role($user), $guest);
		$this->addRole(new Zend_Acl_Role($admin), $user);
		
		$this->allow($guest, 'user', array('register', 'login'));
		$this->allow($guest, 'book', array('index', 'detail'));
		$this->allow($guest, 'error');
		
		$this->allow($user, 'user', array('logout', 'profile'));
		$this->deny($user, 'user', array('login', 'register'));
		$this->allow($user, 'book', 'favorite');
		
		$this->allow($admin, 'book', array('new', 'edit', 'delete'));
	}
	
}