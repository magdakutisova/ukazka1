<?php
class BookControllerTest extends Zend_Test_PHPUnit_ControllerTestCase{
	
	public function setUp(){
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
	}
	
	public function getCsrfToken(){
		$html = $this->getResponse()->getBody();
		$dom = new Zend_Dom_Query($html);
		$csrf = $dom->query('#csrf')->current()->getAttribute('value');
		return $csrf;
	}
	
	public function userLogin(){
		$this->dispatch('/user/login');
		$this->request->setMethod('POST')
			->setPost(array(
				'email' => 'test@test.cz',
				'password' => 'test',
				'csrf' => $this->getCsrfToken(),
		));
		$this->dispatch('/user/login');
		$this->assertRedirectTo('/book');
		$this->resetRequest()->resetResponse();
	}
	
	public function testRunIndexAction(){
		$this->dispatch('/book');
		$this->assertController('book');
		$this->assertAction('index');
	}
	
	public function testNewActionContainsForm(){
		$this->userLogin();
		$this->dispatch('/book/new');
		$this->assertController('book');
		$this->assertAction('new');
		$this->assertQueryCount('form#book', 1);
	}
	
	public function testAddedBookRedirectsToBookIndexAndContainsBook(){
		$this->userLogin();
		$this->dispatch('/book/new');
		$csrf = $this->getCsrfToken();
		$this->resetRequest()->resetResponse();
		$this->getRequest()->setMethod('POST');
		$this->getRequest()->setPost(array(
					'name' => 'test',
					'author' => 'test',
					'description' => 'test',
					'price' => 10,
					'stock' => 1,
					'submit' => 'Přidat',
					'csrf' => $csrf,
					));
		$this->dispatch('/book/new');
		//zde test selhává protože nelze simulovat vkládání souboru a formulář neprojde validací
		//po odmazání řádku if($form->isValid($request->getPost())){ z newAction() v BookControlleru
		//test funguje. Chyba Zendu: http://framework.zend.com/issues/browse/ZF-3791
		$this->assertController('book');
		$this->assertAction('new');
		
		$this->assertRedirectTo('/book');
		
		$this->resetRequest()->resetResponse();
		
		$this->dispatch('/book');
		$this->assertQueryContentContains('td', 'test');
	}
	
}