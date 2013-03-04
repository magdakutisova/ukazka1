<?php

class Application_Form_Book extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
        		'label' => 'Název knihy:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'StringLength',
        						'options' => array(1, 200))
        				),
        		));
        
        $this->addElement('text', 'author', array(
        		'label' => 'Autor:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'StringLength',
        						'options' => array(1, 100))
        				),
        		));
        
        $this->addElement('textarea', 'description', array(
        		'label' => 'Popis:',
        		'required' => false,
        		));
        
        $this->addElement('text', 'price', array(
        		'label' => 'Cena v Kč:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'Float',
        						'options' => array('locale' => 'cs'))
        		),
        ));
        
        $this->addElement('text', 'stock', array(
        		'label' => 'Počet ks na skladě:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'Int',
        						'options' => array('locale' => 'cs'))
        				,
        				array('validator' => 'GreaterThan',
        				'options' => array('min' => -1)),
        				)
        ));
        
        $this->addElement('submit', 'submit', array(
        		'ignore' => true,
        		'label' => 'Uložit knihu',
        		));
        
        $this->addElement('hash', 'csrf', array(
        		'ignore' => 'true',
        		));
    }


}

