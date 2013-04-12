<?php
class BookControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    /**
     * Získá hodnotu elementu csrf z načteného formuláře.
     * @return string csrf
     *
     */
    public function getCsrfToken()
    {
		$html = $this->getResponse()->getBody();
		$dom = new Zend_Dom_Query($html);
		$csrf = $dom->query('#csrf')->current()->getAttribute('value');
		return $csrf;
    }

    /**
     * Přihlásí uživatele.
     *
     */
    public function userLogin()
    {
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

    public function testRunIndexAction()
    {
		$this->dispatch('/book');
		$this->assertResponseCode(200);
		$this->assertController('book');
		$this->assertAction('index');
		$this->assertRoute('bookIndex');
    }

    public function testNewActionContainsForm()
    {
		$this->userLogin();
		$this->dispatch('/book/new');
		$this->assertController('book');
		$this->assertAction('new');
		$this->assertQueryCount('form#book', 1);
    }

    public function testAddedBookRedirectsToBookIndex()
    {
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
					'image' => '',
					'csrf' => $csrf,
					));
		$this->dispatch('/book/new');
		//zde test selhává protože nelze simulovat vkládání souboru a formulář neprojde validací
		//po zakomentování řádku if($form->isValid($request->getPost())){ z newAction() v BookControlleru
		//test funguje. Chyba Zendu: http://framework.zend.com/issues/browse/ZF-3791
		$this->assertResponseCode(302);
		$this->assertRedirectTo('/book');
    }

}

