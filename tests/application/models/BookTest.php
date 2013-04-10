<?php
class BookTest extends PHPUnit_Framework_Testcase{
	
	public function setUp(){
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
		parent::setUp();
	}
	
	public function testBookInitiaState(){
		$book = new Application_Model_Book();
		
		$this->assertNull($book->idBook, 'proměnná "idBook" musí být zpočátku prázdná');
		$this->assertNull($book->name, 'proměnná "name" musí být zpočátku prázdná');
		$this->assertNull($book->author, 'proměnná "author" musí být zpočátku prázdná');
		$this->assertNull($book->description, 'proměnná "description" musí být zpočátku prázdná');
		$this->assertNull($book->price, 'proměnná "price" musí být zpočátku prázdná');
		$this->assertNull($book->stock, 'proměnná "stock" musí být zpočátku prázdná');
		$this->assertNull($book->image, 'proměnná "image" musí být zpočátku prázdná');
	}
	
	public function testExchangeArraySetsPropertiesCorrectly(){
		$book = new Application_Model_Book();
		$data = array(
				'idBook' => 1,
				'name' => 'test',
				'author' => 'test',
				'description' => 'test',
				'price' => 100,
				'stock' => 100,
				'image' => 'test.jpg',
		);
	
		$book->exchangeArray($data);
	
		$this->assertSame($data['idBook'], $book->idBook, 'proměnná "idBook" nebyla správně nastavena');
		$this->assertSame($data['name'], $book->name, 'proměnná "name" nebyla správně nastavena');
		$this->assertSame($data['author'], $book->author, 'proměnná "author" nebyla správně nastavena');
		$this->assertSame($data['description'], $book->description, 'proměnná "description" nebyla správně nastavena');
		$this->assertSame($data['price'], $book->price, 'proměnná "price" nebyla správně nastavena');
		$this->assertSame($data['stock'], $book->stock, 'proměnná "stock" nebyla správně nastavena');
		$this->assertSame($data['image'], $book->image, 'proměnná "image" nebyla správně nastavena');
	}
	
	public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent(){
		$book = new Application_Model_Book();
		$book->exchangeArray(array(
				'idBook' => 1,
				'name' => 'test',
				'author' => 'test',
				'description' => 'test',
				'price' => 100,
				'stock' => 100,
				'image' => 'test.jpg',
		));
		$book->exchangeArray(array());
	
		$this->assertNull($book->idBook, 'proměnná "idBook" má být null');
		$this->assertNull($book->name, 'proměnná "name" má být null');
		$this->assertNull($book->author, 'proměnná "author" má být null');
		$this->assertNull($book->description, 'proměnná "description" má být null');
		$this->assertNull($book->price, 'proměnná "price" má být null');
		$this->assertNull($book->stock, 'proměnná "stock" má být null');
		$this->assertNull($book->image, 'proměnná "image" má být null');
	}
	
	public function testGetArrayCopyReturnsAnArrayWithPropertyValues(){
		$book = new Application_Model_Book();
		$data = array(
				'idBook' => 1,
				'name' => 'test',
				'author' => 'test',
				'description' => 'test',
				'price' => 100,
				'stock' => 100,
				'image' => 'test.jpg',
		);
	
		$book->exchangeArray($data);
		$copyArray = $book->toArray();
	
		$this->assertSame($data['idBook'], $copyArray['idBook'], 'proměnná "idBook" nebyla správně nastavena');
		$this->assertSame($data['name'], $copyArray['name'], 'proměnná "name" nebyla správně nastavena');
		$this->assertSame($data['author'], $copyArray['author'], 'proměnná "author" nebyla správně nastavena');
		$this->assertSame($data['description'], $copyArray['description'], 'proměnná "description" nebyla správně nastavena');
		$this->assertSame($data['price'], $copyArray['price'], 'proměnná "price" nebyla správně nastavena');
		$this->assertSame($data['stock'], $copyArray['stock'], 'proměnná "stock" nebyla správně nastavena');
		$this->assertSame($data['image'], $copyArray['image'], 'proměnná "image" nebyla správně nastavena');
	}
	
}