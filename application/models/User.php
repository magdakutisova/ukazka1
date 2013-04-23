<?php

/**
 * Třída mapující záznam tabulky user na objekt User.
 * @author Magda Kutišová
 *
 */
class Application_Model_User
{

	protected $idUser;
	protected $email;
	protected $password;
	protected $salt;
	protected $role;
	
	/**
	 * Vytvoří instanci a nastaví atributy.
	 * @param array $options atributy k nastavení
	 */
	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	/**
	 * Implementace magic metody __set.
	 * @param unknown $name jméno atributu k nastavení
	 * @param unknown $value hodnota atributu k nastavení
	 * @throws Exception pokud je atribut neplatný
	 */
	public function __set($name, $value){
		$method = 'set' . $name;
		if(('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Neplatný atribut uživatele.');
		}
		$this->$method($value);
	}
	
	/**
	 * Implementace magic metody __get.
	 * @param unknown $name jméno požadovaného atributu
	 * @throws Exception pokud je atribut neplatný
	 */
	public function __get($name){
		$method = 'get' . $name;
		if(('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Neplatný atribut uživatele.');
		}
		return $this->$method();
	}
	
	/**
	 * Nastavení atributů instance.
	 * @param array $options atributy instance
	 * @return Application_Model_User instance s nastavenými atributy
	 */
	public function setOptions(array $options){
		$methods = get_class_methods($this);
		foreach ($options as $key => $value){
			$method = 'set' . ucfirst($key);
			if(in_array($method, $methods)){
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function setIdUser($idUser){
		$this->idUser = $idUser;
		return $this;
	}
	
	public function getIdUser(){
		return $this->idUser;
	}
	
	public function setEmail($email){
		$this->email = $email;
		return $this;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setPassword($password){
		$this->password = $password;
		return $this;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function setSalt($salt){
		$this->salt = $salt;
		return $this;
	}
	
	public function getSalt(){
		return $this->salt;
	}
	
	public function setRole($role){
		$this->role = $role;
		return $this;
	}
	
	public function getRole(){
		return $this->role;
	}
	
	/**
	 * Vrátí pole parametrů třídy.
	 * @return multitype: pole parametrů třídy
	 */
	public function toArray(){
		return get_object_vars($this);
	}	
	
	/**
	 * Načte proměnné z pole do třídy.
	 * @param unknown $data pole proměnných k nastavení třídě
	 */
	public function exchangeArray($data){
		$this->idUser = (isset($data['idUser'])) ? $data['idUser'] : null;
		$this->email = (isset($data['email'])) ? $data['email'] : null;
		$this->password = (isset($data['password'])) ? $data['password'] : null;
		$this->salt = (isset($data['salt'])) ? $data['salt'] : null;
		$this->role = (isset($data['role'])) ? $data['role'] : null;
	}
	
}

