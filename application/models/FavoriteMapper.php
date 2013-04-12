<?php
class Application_Model_FavoriteMapper{
	
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
			$this->setDbTable('Application_Model_DbTable_Favorite');
		}
		return $this->dbTable;
	}
	
	public function favorite($idBook, $idUser){
		try{
			$this->getDbTable()->insert(array(
						'idBook' => $idBook,
						'idUser' => $idUser)
			);
			return true;
		}
		catch(Zend_Exception $e){
			return false;
		}		
	}
	
}