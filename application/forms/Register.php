<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
         $this->setName('register');
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
        
        $this->addElement('password', 'confirmPassword', array(
        		'label' => 'Heslo znovu',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'Identical',
        						'options' => array('token' => 'password'))
        				),
        		));
        
        $this->addElement('submit', 'create', array(
        		'label' => 'Zaregistrovat',
        		));
    }


}

