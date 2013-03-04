<?php

class Application_Model_BookMapper
{
	
	protected $dbTable;
	
	/***************************************************************
	 * Nastaví do proměnné $dbTable instanci brány k databázové tabulce.
	 */
	public function setDbTable($dbTable){
		if(is_string($dbTable)){
			$dbTable = new $dbTable();
		}
		if(!$dbTable instanceof Zend_Db_Table_Abstract){
			throw new Exception('Byla poskytnuta neplatná brána databázové tabulky.');
		}
		$this->dbTable = $dbTable;
		return $this;
	}
	
	/****************************************************************
	 * Vrátí odkaz na instanci brány k databázové tabulce.
	 */
	public function getDbTable(){
		if(null === $this->dbTable){
			$this->setDbTable('Application_Model_DbTable_Book');
		}
		return $this->dbTable;
	}
	
	/*****
	 * Uloží knihu do databáze.
	 */
	public function save(Application_Model_Book $book){
		$data = array(
				'name' => $book->getName(),
				'author' => $book->getAuthor(),
				'description' => $book->getDescription(),
				'price' => $book->getPrice(),
				'stock' => $book->getStock(),
				);
		
		if(null === ($idBook = $book->getIdBook())){
			unset($data['idBook']);
			$this->getDbTable()->insert($data);
		}
		else{
			$this->getDbTable()->update($data, array('idBook = ?' => $idBook));
		}
	}
	
	/******
	 * Nalezne knihu v databázi podle zadaného ID a vrátí příslušnou instanci třídy
	 * Application_Model_Book.
	 */
	public function find($idBook, Application_Model_Book $book){
		$result = $this->getDbTable()->find($idBook);
		if(0 == count($result)){
			return;
		}
		$row = $result->current();
		$book->setIdBook($row->idBook)
			 ->setName($row->name)
			 ->setAuthor($row->author)
			 ->setDescription($row->description)
			 ->setPrice($row->price)
			 ->setStock($row->stock);
		return $book;
	}
	
	/*****
	 * Vrátí pole všech knih z databáze.
	 */
	public function fetchAll(){
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach($resultSet as $row){
			$entry = new Application_Model_Book();
			$entry->setIdBook($row->idBook)
			      ->setName($row->name)
			      ->setAuthor($row->author)
			      ->setDescription($row->description)
			      ->setPrice($row->price)
			      ->setStock($row->stock);
			$entries[] = $entry;
		}
		return $entries;
	}

}

