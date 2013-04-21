<?php

/**
 * Třída pro práci s databázovou tabulkou book.
 * @author Magda Kutišová
 *
 */
class Application_Model_BookMapper
{
	
	protected $dbTable;
	
	/**
	 * Nastaví do proměnné $dbTable instanci brány k databázové tabulce.
	 * 
	 * @param unknown $dbTable instance brány k databázi
	 * @throws Exception pokud je poskytnutá brána neplatná
	 * @return Application_Model_BookMapper instance třídy pro práci s databází s nastavenou bránou k databázi
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
	
	/**
	 * Vrátí odkaz na instanci brány k databázové tabulce.
	 * @return Ambigous <unknown, Zend_Db_Table_Abstract, unknown> instance brány k databázové tabulce
	 */
	public function getDbTable(){
		if(null === $this->dbTable){
			$this->setDbTable('Application_Model_DbTable_Book');
		}
		return $this->dbTable;
	}
	
	/**
	 * Vrátí pole všech knih z databáze.
	 * @return multitype:Application_Model_Book |unknown pole knih nebo data z cache
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
	
	/**
	 * Vrátí oblíbené knihy uživatele.
	 * 
	 * @param unknown $idUser ID uživatele
	 * @return multitype:Application_Model_Book pole oblíbených knih
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
	
	/**
	 * Nalezne knihu v databázi podle zadaného ID a vrátí příslušnou instanci třídy
	 * Application_Model_Book.
	 * @param unknown $idBook ID knihy k nalezení
	 * @param Application_Model_Book $book instance modelové třídy book pro naplnění daty z DB
	 * @throws Exception pokud kniha není nalezena
	 * @return Application_Model_Book|Ambigous <Zend_Db_Table_Rowset_Abstract, unknown> instance modelové třídy Book nebo data z cache
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
	
	/**
	 * Uloží knihu do databáze.
	 * @param Application_Model_Book $book kniha k uložení
	 * @throws Exception pokud v DB neexistuje ID nastavené u knihy
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
	
	/**
	 * Smaže knihu z databáze.
	 * @param unknown $idBook ID knihy ke smazání
	 */
	public function delete($idBook){
		$this->getDbTable()->delete(array('idBook = ?' => $idBook));
		$cache = Zend_Registry::get('cache');
		$cache->remove('books');
	}

}

