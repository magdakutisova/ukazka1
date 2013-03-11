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
        
        //ACL
        $acl = new My_Controller_Helper_Acl();
        $email = '';
        $role = 3;
        if(Zend_Auth::getInstance()->hasIdentity()){
        	$role = Zend_Auth::getInstance()->getIdentity()->role;
        }
        $this->view->canAddBooks = $acl->isAllowed($role, 'book', 'new');
        $this->view->canEditBooks = $acl->isAllowed($role, 'book', 'edit');
        $this->view->canDeleteBooks = $acl->isAllowed($role, 'book', 'delete');
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
        		//prejmenovani souboru, pokud existuje
        		$newFileName = '';
        		if($form->image->isUploaded()){
        			$originalFileName = pathinfo($form->image->getFileName());
        			$author = $form->getValue('author');
        			$name = $form->getValue('name');
        			$new = preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $author . ' ' . $name));
        			$newFileName = $new . '.' . $originalFileName['extension'];
        			$form->image->addFilter('Rename', $newFileName);
        		}
        		$entry = new Application_Model_Book($form->getValues());
        		if($form->image->isReceived()){
        			$entry->image = $newFileName;
        		}
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
        $idBook = $request->getParam('idBook', 0);
        if(!$idBook){
        	return $this->_helper->redirector()->gotoRoute(array(), 'bookNew');
        }
        $mapper = new Application_Model_BookMapper();
        $data = $mapper->find($idBook, new Application_Model_Book());
        $form->populate($data->toArray());

        if($request->isPost()){
        	if($form->isValid($request->getPost())){
        		//prejmenovani souboru, pokud existuje
        		$newFileName = '';
        		if($form->image->isUploaded()){
        			$originalFileName = pathinfo($form->image->getFileName());
        			$author = $form->getValue('author');
        			$name = $form->getValue('name');
        			$new = preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $author . ' ' . $name));
        			$newFileName = $new . '.' . $originalFileName['extension'];
        			$form->image->addFilter('Rename', $newFileName);
        		}
        		$entry = new Application_Model_Book($form->getValues());
        		if($form->image->isReceived()){
        			$entry->image = $newFileName;
        		}
        		$mapper->save($entry);
        		return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    public function deleteAction()
    {
    	$request = $this->getRequest();
        $idBook = $request->getParam('idBook', 0);
        $mapper = new Application_Model_BookMapper();
        if(!$idBook){
        	return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        }
        if($request->isPost()){
        	$del = $request->getParam('del', 'Ne');
        	if($del == 'Ano'){
        		$mapper->delete($idBook);
        	}
        	return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        }
        $this->view->book = $mapper->find($idBook, new Application_Model_Book());
    }

}