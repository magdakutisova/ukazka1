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
		$cache = Zend_Registry::get('cache');
		if(!$result = $cache->load('books')){
			$resultSet = $this->getDbTable()->fetchAll();
			$entries = array();
			foreach($resultSet as $row){
				$entry = new Application_Model_Book();
				$entry->exchangeArray($row->toArray());
				$entries[] = $entry;
			}
			$cache->save($entries, 'books');
			return $entries;
		}
		else{
			return $result;
		}		
	}
	
	/****
	 * Vrátí pole oblíbených knih uživatele.
	 */
	public function fetchFavorites($idUser){
		$select = $this->getDbTable()->select()
			->from('book')
			->join('favorite', 'book.idBook = favorite.idBook')
			->where('idUser = ?', $idUser);
		$select->setIntegrityCheck(false);
		$resultSet = $this->getDbTable()->fetchAll($select);
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
		$cache = Zend_Registry::get('cache');
		if(!$result = $cache->load('book' . $idBook)){
			$idBook = (int) $idBook;
			$result = $this->getDbTable()->find($idBook);
			if(0 == count($result)){
				throw new Exception("Kniha $idBook nebyla nalezena");
			}
			$row = $result->current();
			$book->exchangeArray($row->toArray());
			$cache->save($book, 'book' . $idBook);
			return $book;
		}
		else{
			return $result;
		}
		
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
			$cache = Zend_Registry::get('cache');
			$cache->remove('books');
		}
		else{
			if($this->find($idBook, $book)){
				$this->getDbTable()->update($data, array('idBook = ?' => $idBook));
				$cache = Zend_Registry::get('cache');
				$cache->remove('books');
				$cache->remove('book' . $idBook);
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
		$this->getDbTable()->delete(array('idBook = ?' => $idBook));
		$cache = Zend_Registry::get('cache');
		$cache->remove('books');
	}

}

