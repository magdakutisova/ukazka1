<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    { 	
    	$this->setName('login');
    	$this->setMethod('post');
    	
    	//DEKORÁTORY
    	$this->setDecorators(array(
    			'FormElements',
    			array('HtmlTag', array('tag' => 'table')),
    			'Form',
    	));
    	
    	$elementDecorator = array(
    			'ViewHelper',
    			array('Errors'),
    			array(array('data' => 'HtmlTag'), array('tag' => 'td')),
    			array('Label', array('tag' => 'td')),
    			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    	);
    	
    	$buttonDecorator = array(
    			'ViewHelper',
    			array('Errors'),
    			array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => 2)),
    			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    	);
    	
    	$hiddenDecorator = array(
    			'ViewHelper',
    			);
    	
    	//ELEMENTY
    	$this->addElement('text', 'email', array(
    			'label' => 'E-mail',
    			'required' => true,
    			'filters' => array('StringTrim', 'StringtoLower'),
    			'validators' => array(
    					'EmailAddress',
    					array('validator' => 'StringLength',
    							'options' => array(1,255))
    			),
    			'decorators' => $elementDecorator,
    	));

    	$this->addElement('password', 'password', array(
    			'label' => 'Heslo',
    			'required' => true,
    			'filters' => array('StringTrim'),
    			'validators' => array(
    					'Alnum',
    					array('validator' => 'StringLength',
    							'options' => array(1,50))
    			),
    			'decorators' => $elementDecorator,
    	));

    	$this->addElement('submit', 'login', array(
    			'label' => 'Přihlásit',
    			'decorators' => $buttonDecorator,
    	));
    	
    	$this->addElement('hash', 'csrf', array(
    			'ignore' => 'true',
    			'decorators' => $hiddenDecorator,
    	));
    }


}

