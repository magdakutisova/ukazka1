<?php

/**
 * Controller pro manipulaci s knihami.
 * 
 * @author Magda Kutišová
 *
 */
class BookController extends Zend_Controller_Action
{

	/**
	 * Akce pro zobrazení seznamu všech knih.
	 */
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
        $this->view->canFavoriteBooks = $acl->isAllowed($role, 'book', 'favorite');
    }

    /**
     * Akce pro zobrazení detailu knihy.
     */
    public function detailAction()
    {
   		$idBook = $this->getRequest()->getParam('idBook');
   		if(!$idBook){
   			return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
   		}
   		
   		$book = new Application_Model_BookMapper();
   		$this->view->entry = $book->find($idBook, new Application_Model_Book());
    }

    /**
     * Akce pro přidání nové knihy do databáze.
     */
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
        		$form->getValues();
        		$entry = new Application_Model_Book($request->getPost());
        		if($form->image->isReceived()){
        			$entry->image = $newFileName;
        		}
        		$mapper = new Application_Model_BookMapper();
        		$mapper->save($entry);
        		
        		$this->_helper->FlashMessenger('Kniha byla přidána');
        		return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    /**
     * Akce pro editaci knihy.
     */
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
        		
        		$this->_helper->FlashMessenger('Kniha byla upravena');
        		return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        	}
        }
        
        $this->view->form = $form;
    }

    /**
     * Akce pro smazání knihy.
     */
    public function deleteAction()
    {
    	$request = $this->getRequest();
        $idBook = $request->getParam('idBook', 0);
        $mapper = new Application_Model_BookMapper();
        if(!$idBook){
        	return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        }
        if($request->isPost()){
        	$del = $request->getParam('del', 'Ne');
        	if($del == 'Ano'){
        		$mapper->delete($idBook);
        	}
        	$this->_helper->FlashMessenger('Kniha byla smazána');
        	return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
        }
        $this->view->book = $mapper->find($idBook, new Application_Model_Book());
    }

    /**
     * Akce pro přidání knihy do seznamu oblíbených.
     */
    public function favoriteAction()
    {
        $request = $this->getRequest();
    	$idBook = $this->getParam('idBook', 0);
    	$mapper = new Application_Model_FavoriteMapper();
    	if(!$idBook){
    		return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
    	}
    	if(!Zend_Auth::getInstance()->hasIdentity()){
    		return $this->_helper->redirector->gotoSimple('denied', 'error');
    	}
    	$idUser = Zend_Auth::getInstance()->getIdentity()->idUser;
    	$result = $mapper->favorite($idBook, $idUser);
    	if($result){
    		$this->_helper->FlashMessenger('Kniha byla přidána do seznamu oblíbených');
    	}
    	else{
    		$this->_helper->FlashMessenger('Tuto knihu již máte v seznamu oblíbených');
    	}
    	return $this->_helper->redirector->gotoRoute(array(), 'bookIndex');
    }


}

