<?php

/**
 * Třída obsahující nastavení formuláře pro registraci nového uživatele.
 * @author Magda Kutišová
 *
 */
class Application_Form_Register extends Zend_Form
{

	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::init()
	 */
    public function init()
    {
        $this->setName('register');
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
        
        $captchaDecorator = array(
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
        
        $this->addElement('password', 'confirmPassword', array(
        		'label' => 'Heslo znovu',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'Identical',
        						'options' => array('token' => 'password'))
        				),
        		'decorators' => $elementDecorator,
        		));
        
        $this->addElement('captcha', 'captcha', array(
        		'label' => 'Prosím, opište text',
        		'captcha' => array(
        				'captcha' => 'Figlet',
        				'wordLen' => 6,
        				'timeout' => 300,
        				),
        		'decorators' => $captchaDecorator,
        		));
        
        $this->addElement('submit', 'create', array(
        		'label' => 'Zaregistrovat',
        		'decorators' => $buttonDecorator,
        		));
        
        $this->addElement('hash', 'csrf', array(
        		'ignore' => 'true',
        		'decorators' => $hiddenDecorator,
        ));
    }


}

