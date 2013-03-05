<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDoctype(){
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
		$view->setEncoding('UTF-8');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
	}
	
	protected function _initTranslator(){
		$translator = new Zend_Translate(
				array(
						'adapter' => 'array',
						'content' => APPLICATION_PATH . '/../resources/languages',
						'locale' => 'cs',
				)
		);
		Zend_Validate_Abstract::setDefaultTranslator($translator);
	}
	
	protected function _initRouter(array $options = array()){
		$this->bootstrap('FrontController');
		$frontController = $this->getResource('FrontController');
		$router = $frontController->getRouter();
		
		$router->addRoute('bookIndex',
				new Zend_Controller_Router_Route('knihy',
						array('controller' => 'book',
								'action' => 'index'))
				);
		
		$router->addRoute('bookDetail',
				new Zend_Controller_Router_Route('kniha/:idBook/detail',
						array('controller' => 'book',
								'action' => 'detail'))
				);
		
		$router->addRoute('bookNew',
				new Zend_Controller_Router_Route('kniha/nova',
						array('controller' => 'book',
								'action' => 'new'))
				);
		
		$router->addRoute('bookEdit',
				new Zend_Controller_Router_Route('kniha/:idBook/upravit',
						array('controller' => 'book',
								'action' => 'edit'))
		);
		
		$router->addRoute('bookDelete',
				new Zend_Controller_Router_Route('kniha/:idBook/smazat',
						array('controller' => 'book',
								'action' => 'delete'))
		);
		
		$router->addRoute('userRegister',
				new Zend_Controller_Router_Route('registrace',
						array('controller' => 'user',
								'action' => 'register'))
		);
	}
	
}

