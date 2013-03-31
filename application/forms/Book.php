<?php

class Application_Form_Book extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');
        
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
        
        $fileDecorator = array(
        		'File',
        		array('Errors'),
        		array(array('data' => 'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        );
        
        $hiddenDecorator = array(
        		'ViewHelper',
        );
        
        //ELEMENTY
        $this->addElement('hidden', 'idBook', array(
        		'validators' => array('Int'),
        		'decorators' => $hiddenDecorator,
        		));
        
        $this->addElement('text', 'name', array(
        		'label' => 'Název knihy:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'StringLength',
        						'options' => array(1, 200))
        				),
        		'decorators' => $elementDecorator,
        		));
        
        $this->addElement('text', 'author', array(
        		'label' => 'Autor:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'StringLength',
        						'options' => array(1, 100))
        				),
        		'decorators' => $elementDecorator,
        		));
        
        $this->addElement('textarea', 'description', array(
        		'label' => 'Popis:',
        		'required' => false,
        		'decorators' => $elementDecorator,
        		));
        
        $this->addElement('text', 'price', array(
        		'label' => 'Cena v Kč:',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array(
        				array('validator' => 'Float',
        						'options' => array('locale' => 'cs'))
        		),
        		'decorators' => $elementDecorator,
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
        				),
        		'decorators' => $elementDecorator,
        ));
        
        $this->addElement('file', 'image', array(
        		'label' => 'Obálka knihy:',
        		'destination' => PUBLIC_PATH . '/images',
        		'validators' => array(
        				array('validator' => 'Count',
        						'options' => 1),
        				array('validator' => 'IsImage'),
        				),
        		'decorators' => $fileDecorator,
        		));
        
        $this->addElement('submit', 'submit', array(
        		'ignore' => true,
        		'label' => 'Uložit knihu',
        		'decorators' => $buttonDecorator,
        		));
        
        $this->addElement('hash', 'csrf', array(
        		'ignore' => 'true',
        		'decorators' => $hiddenDecorator,
        		));
        
    }


}

