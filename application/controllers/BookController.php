<?php

class BookController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $cache = Zend_Registry::get('cache');
        if(!$result = $cache->load('books')){
        	$book = new Application_Model_BookMapper();
        	$books = $book->fetchAll();
        	$cache->save($books, 'books');
        	$this->view->entries = $books;
        }
    	else{
    		$this->view->entries = $result;
    	}
        
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
   		if(!$idBook){
   			return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
   		}
   		
   		$cache = Zend_Registry::get('cache');
        if(!$result = $cache->load('book' . $idBook)){
        	$book = new Application_Model_BookMapper();
        	$bookResult = $book->find($idBook, new Application_Model_Book());
        	$cache->save($bookResult, 'book' . $idBook);
        	$this->view->entry = $bookResult;
        }
    	else{
    		$this->view->entry = $result;
    	}
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Book();
        $form->submit->setLabel('Přidat');
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
        		
        		$cache = Zend_Registry::get('cache');
        		$cache->remove('books');
        		
        		$this->_helper->FlashMessenger('Kniha byla přidána');
        		return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    public function editAction()
    {
    	$request = $this->getRequest();
        $form = new Application_Form_Book();
        $form->submit->setLabel('Upravit');
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
        		
        		$cache = Zend_Registry::get('cache');
        		$cache->remove('book' . $entry->idBook);
        		$cache->remove('books');
        		
        		$this->_helper->FlashMessenger('Kniha byla upravena');
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
        	$this->_helper->FlashMessenger('Kniha byla smazána');
        	return $this->_helper->redirector()->gotoRoute(array(), 'bookIndex');
        }
        $this->view->book = $mapper->find($idBook, new Application_Model_Book());
    }

}