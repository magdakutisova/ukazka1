<?php

class Application_Model_UserMapper
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
			$this->setDbTable('Application_Model_DbTable_User');
		}
		return $this->dbTable;
	}
	
	/*****
	 * Nalezne uživatele v databázi podle zadaného ID a vrátí příslušnou instanci třídy
	 * Application_Model_User.
	 */
	public function find($idUser, Application_Model_User $user){
		$idUser = (int) $idUser;
		$result = $this->getDbTable()->find($idUser);
		if(0 == count($result)){
			throw new Exception("Uživatel $idUser nebyl nalezen.");
		}
		$row = $result->current();
		$user->exchangeArray($row->toArray());
		return $user;
	}
	
	/******
	 * Nalezne užvatele v databázi podle emailu a vrátí příslušnou instanci třídy
	 * Application_Model_User.
	 */
	public function findByEmail($email, Application_Model_User $user){
		$select = $this->getDbTable()->select()
			->from('user')
			->where('email = ?', $email);
		$result = $this->getDbTable()->fetchAll($select);
		if(0 == count($result)){
			return null;
		}
		$row = $result->current();
		$user->exchangeArray($row->toArray());
		return $user;
	}
	
	/*****
	 * Uloží uživatele do databáze.
	*/
	public function save(Application_Model_User $user){
		$data = $user->toArray();
		if(null === ($idUser = $user->getIdUser())){
			unset($data['idUser']);
			$this->getDbTable()->insert($data);
		}
		else{
			if($this->find($idUser, $user)){
				$this->getDbTable()->update($data, array('idUser = ?' => $idUser));
			}
			else{
				throw new Exception("Zadané id neexistuje.");
			}
		}
	}
}

