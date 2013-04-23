<?php
/**
 * Třída pro práci s databázovou tabulkou favorite.
 * @author Magda Kutišová
 */
class Application_Model_FavoriteMapper{
	
	protected $dbTable;
	
	/**
	 * Nastaví do proměnné $dbTable instanci brány k databázové tabulce.
	 * @param unknown $dbTable instance brány k databázi
	 * @throws Exception pokud je poskytnutá brána neplatná
	 * @return Application_Model_FavoriteMapper instance třídy pro práci s databází s nastavenou bránou k databázi
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
			$this->setDbTable('Application_Model_DbTable_Favorite');
		}
		return $this->dbTable;
	}
	
	/**
	 * Funkce pro přidání knihy do oblíbených v databázi.
	 * @param unknown $idBook ID oblíbené knihy
	 * @param unknown $idUser ID uživatele
	 * @return boolean true, pokud přidáno, false, pokud již uživatel tuto knihu v oblíbených má
	 */
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