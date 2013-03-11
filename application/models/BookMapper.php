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
	 * Vrátí pole všech knih z databáze.
	*/
	public function fetchAll(){
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach($resultSet as $row){
			$entry = new Application_Model_Book();
			$entry->exchangeArray($row->toArray());
			$entries[] = $entry;
		}
		return $entries;
	}
	
	/******
	 * Nalezne knihu v databázi podle zadaného ID a vrátí příslušnou instanci třídy
	 * Application_Model_Book.
	 */
	public function find($idBook, Application_Model_Book $book){
		$idBook = (int) $idBook;
		$result = $this->getDbTable()->find($idBook);
		if(0 == count($result)){
			throw new Exception("Kniha $idBook nebyla nalezena");
		}
		$row = $result->current();
		$book->exchangeArray($row->toArray());
		return $book;
	}
	
	/*****
	 * Uloží knihu do databáze.
	*/
	public function save(Application_Model_Book $book){
		$data = $book->toArray();
		if(null == ($image = $book->image)){
			unset($data['image']);
		}
		if(null == ($idBook = $book->idBook)){
			unset($data['idBook']);
			$this->getDbTable()->insert($data);
		}
		else{
			if($this->find($idBook, $book)){
				$this->getDbTable()->update($data, array('idBook = ?' => $idBook));
			}
			else{
				throw new Exception("Zadané id neexistuje");
			}
		}
	}
	
	/*****
	 * Smaže knihu z databáze.
	*/
	public function delete($idBook){
		$this->getDbTable()->delete('idBook = ' . (int) $idBook);
	}

}

