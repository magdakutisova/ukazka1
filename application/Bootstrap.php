<?php

/**
 * Zaváděcí soubor aplikace.
 * @author Magda Kutišová
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * Nastavení doctype.
	 */
	protected function _initDoctype(){
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}
	
	/**
	 * Nastavení meta tagů.
	 */
	protected function _initMeta(){
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
	}
	
	/**
	 * Nastavení překladače.
	 */
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
	
	/**
	 * Nastavení automatického načítání souborů.
	 */
	protected function _initAutoload() {
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('My_');
		Zend_Controller_Action_HelperBroker::addPrefix('My_Controller_Helper');
	}
	
	/**
	 * Nastavení ACL.
	 */
	protected function _initAcl(){
		$acl = new My_Controller_Helper_Acl();
		$fc = Zend_Controller_Front::getInstance();
		$fc->registerPlugin(new My_Plugin_Acl($acl));
	}
	
	/**
	 * Nastavení cache.
	 */
	protected function _initCache(){
		$frontend = array(
				'lifetime' => 600,
				'automatic_serialization' => true,
				);
		$backend = array(
				'cache_dir' => '../data/cache/',
				);
		$cache = Zend_Cache::factory('Core', 'File', $frontend, $backend);
		Zend_Registry::set('cache', $cache);
	}
	
	/**
	 * Nastavení routování
	 * @param array $options možnosti
	 */
	protected function _initRouter(array $options = array()){
		$this->bootstrap('FrontController');
		$frontController = $this->getResource('FrontController');
		$router = $frontController->getRouter();
		
		$router->addRoute('bookIndex',
				new Zend_Controller_Router_Route('book',
						array('controller' => 'book',
								'action' => 'index'))
		);
		
		$router->addRoute('bookDetail',
				new Zend_Controller_Router_Route('book/detail/:idBook',
						array('controller' => 'book',
								'action' => 'detail'))
		);
		
		$router->addRoute('bookNew',
				new Zend_Controller_Router_Route('book/new',
						array('controller' => 'book',
								'action' => 'new'))
		);
		
		$router->addRoute('bookEdit',
				new Zend_Controller_Router_Route('book/edit/:idBook',
						array('controller' => 'book',
								'action' => 'edit'))
		);
		
		$router->addRoute('bookDelete',
				new Zend_Controller_Router_Route('book/delete/:idBook',
						array('controller' => 'book',
								'action' => 'delete'))
		);
		
		$router->addRoute('bookFavorite',
				new Zend_Controller_Router_Route('book/favorite/:idBook',
						array('controller' => 'book',
								'action' => 'favorite'))
		);
		
		$router->addRoute('userRegister',
				new Zend_Controller_Router_Route('user/register',
						array('controller' => 'user',
								'action' => 'register'))
		);
		
		$router->addRoute('userLogin',
				new Zend_Controller_Router_Route('user/login',
						array('controller' => 'user',
								'action' => 'login'))
		);
		
		$router->addRoute('userLogout',
				new Zend_Controller_Router_Route('user/logout',
						array('controller' => 'user',
								'action' => 'logout'))
		);
		
		$router->addRoute('userProfile',
				new Zend_Controller_Router_Route('user/profile',
						array('controller' => 'user',
								'action' => 'profile'))
		);
	}
	
}

