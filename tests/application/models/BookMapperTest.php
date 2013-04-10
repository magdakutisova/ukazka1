<?php
class BookMapperTest extends Zend_Test_PHPUnit_DatabaseTestCase{
	
	private $_connectionMock;
	
	//NEFUNKČNÍ NA PHPUNIT 3.6, NUTNO POUŽÍT NIŽŠÍ VERZE
	//http://framework.zend.com/issues/browse/ZF-11781
	
	public function setUp(){
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
		parent::setUp();
	}
	
	protected function getConnection(){
		if($this->_connectionMock == null){
			$connection = Zend_Db::factory(new Zend_Db_Adapter_Pdo_Mysql(array(
					'host' => '127.0.0.1',
					'username' => 'root',
					'password' => '',
					'dbname' => 'ukazkaTest',
					)));
			$this->_connectionMock = $this->createZendDbConnection($connection, 'book');
			Zend_Db_Table_Abstract::setDefaultAdapter($connection);
		}
		return $this->_connectionMock;
	}
	
	protected function getDataSet(){
		return $this->createFlatXmlDataSet(
				dirname(__FILE__) . '/_files/bookSeed.xml'
				);
	}
	
	public function testBookInsertedIntoDatabase(){
		$bookTable = new Application_Model_BookMapper();
		$data = array(
				'name' => 'Pražská zima',
				'author' => 'Madelaine Albrightová',
				'price' => 358,
				'stock' => 54,
				);
		$book = new Application_Model_Book($data);
		$bookTable->save($book);
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet($this->getConnection);
		$ds->addTable('book', 'SELECT * FROM book');
		
		$this->assertDataSetsEqual(
				$this->createFlatXmlDataSet(dirname(__FILE__) . "/_files/bookInsertIntoAssertion.xml"),
				$ds
				);
	}
	
	public function testBookDelete(){
		$bookTable = new Application_Model_BookMapper();
		$bookTable->delete(3);
		
		$ds = new Zend_Test_PHPUnit_Db_DataSet_DbTableDataSet();
		$ds->addTable($bookTable);
		
		$this->assertDataSetsEqual(
				$this->createFlatXmlDataSet(dirname(__FILE__) . "/_files/bookDeleteAssertion.xml"),
				$ds
				);
	}
	
}