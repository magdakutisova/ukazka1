<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    { 	
    	$this->setName('login');
    	$this->setMethod('post');
    	
    		$this->addElement('text', 'email', array(
    				'label' => 'E-mail',
    				'required' => true,
    				'filters' => array('StringTrim', 'StringtoLower'),
    				'validators' => array(
    						'EmailAddress',
    						array('validator' => 'StringLength',
    								'options' => array(0,255))
    				),
    		));
    		
    		$this->addElement('password', 'password', array(
    				'label' => 'Heslo',
    				'required' => true,
    				'filters' => array('StringTrim'),
    				'validators' => array(
    						'Alnum',
    						array('validator' => 'StringLength',
    								'options' => array(0,50))
    				),
    		));
    		
    		$this->addElement('submit', 'login', array(
    				'label' => 'Přihlásit',
    				));
    }


}

