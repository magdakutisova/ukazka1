<?php

class BookController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $book = new Application_Model_BookMapper();
        $this->view->entries = $book->fetchAll();
    }

    public function detailAction()
    {
        $idBook = $this->getRequest()->getParam('idBook');
        $book = new Application_Model_BookMapper();
        $this->view->entry = $book->find($idBook, new Application_Model_Book());
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Book();
        if($request->isPost()){
        	if($form->isValid($request->getPost())){
        		$entry = new Application_Model_Book($form->getValues());
        		$mapper = new Application_Model_BookMapper();
        		$mapper->save($entry);
        		return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    public function editAction()
    {
    	$request = $this->getRequest();
        $form = new Application_Form_Book();
        $idBook = $request->getParam('idBook');
        $mapper = new Application_Model_BookMapper();
        $data = $mapper->findArray($idBook);
        $form->populate($data);

        if($request->isPost()){
        	if($form->isValid($request->getPost())){
        		$entry = new Application_Model_Book($form->getValues());
        		$mapper->save($entry);
        		return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
        	$idBook = $request->getParam('idBook');
        	$mapper = new Application_Model_BookMapper();
        	$mapper->delete($idBook);
        	return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        }
    }


}