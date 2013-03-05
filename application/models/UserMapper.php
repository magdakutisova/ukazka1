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
	 * Uloží uživatele do databáze.
	 */
	public function save(Application_Model_User $user){
		$data = array(
				'email' => $user->getEmail(),
				'password' => $user->getPassword(),
				'salt' => $user->getSalt(),
				'role' => $user->getRole(),
				'firstName' => $user->getFirstName(),
				'surname' => $user->getSurname(),
				'street' => $user->getStreet(),
				'number' => $user->getNumber(),
				'country' => $user->getCountry(),
				);
		if(null === ($idUser = $user->getIdUser())){
			unset($data['idUser']);
			$this->getDbTable()->insert($data);
		}
		else{
			$this->getDbTable()->update($data, array('idUser = ?' => $idUser));
		}
	}
	
	/*****
	 * Nalezne uživatele v databázi podle zadaného ID a vrátí příslušnou instanci třídy
	 * Application_Model_User.
	 */
	public function find($idUser, Application_Model_User $user){
		$result = $this->getDbTable()->find($idUser);
		if(0 == count($result)){
			return;
		}
		$row = $result->current();
		$user->setIdUser($row->idUser)
			 ->setEmail($row->email)
			 ->setPassword($row->password)
			 ->setSalt($row->salt)
			 ->setRole($row->role)
			 ->setFirstName($row->firstName)
			 ->setSurname($row->surname)
			 ->setStreet($row->street)
			 ->setNumber($row->number)
			 ->setCountry($row->country);
		return $user;
	}
	
	/******
	 * Nalezne užvatele v databázi podle emailu a vrátí příslušnou instanci třídy
	 * Application_Model_User.
	 */
	public function findByUsername($email, Application_Model_User $user){
		$select = $this->getDbTable()->select()
			->from('user')
			->where('email = ?', $email);
		$result = $this->getDbTable()->fetchAll($select);
		if(0 == count($result)){
			return;
		}
		$row = $result->current();
		$user->setIdUser($row->idUser)
		->setEmail($row->email)
		->setPassword($row->password)
		->setSalt($row->salt)
		->setRole($row->role)
		->setFirstName($row->firstName)
		->setSurname($row->surname)
		->setStreet($row->street)
		->setNumber($row->number)
		->setCountry($row->country);
		return $user;
	}
	
}

