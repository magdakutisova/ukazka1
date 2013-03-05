<?php

class Application_Form_Book extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->addElement('hidden', 'idBook', array(
        		'validators' => array('Int'),
        		));
        
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
        
        $this->addElement('file', 'image', array(
        		'label' => 'Obálka knihy:',
        		'destination' => PUBLIC_PATH . '/images',
        		'validators' => array(
        				array('validator' => 'Count',
        						'options' => 1),
        				array('validator' => 'Extension',
        						'options' => 'jpg,png,gif'),
        				),
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

