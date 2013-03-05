<?php

class Application_Model_User
{

	protected $idUser;
	protected $email;
	protected $password;
	protected $salt;
	protected $role;
	protected $firstName;
	protected $surname;
	protected $street;
	protected $number;
	protected $country;
	
	/*******************
	 * Vytvoří instanci a nastaví atributy.
	*/
	public function __construct(array $options = null){
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	/*************
	 * Implementace magic metody __set.
	*/
	public function __set($name, $value){
		$method = 'set' . $name;
		if(('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Neplatný atribut uživatele.');
		}
		$this->$method($value);
	}
	
	/*********************
	 * Implementace magic metody __get.
	*/
	public function __get($name){
		$method = 'get' . $name;
		if(('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Neplatný atribut uživatele.');
		}
		return $this->$method();
	}
	
	/**********
	 * Nastavení atributů třídy.
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
	
	public function setFirstName($firstName){
		$this->firstName = $firstName;
		return $this;
	}
	
	public function getFirstName(){
		return $this->firstName;
	}
	
	public function setSurname($surname){
		$this->surname = $surname;
		return $this;
	}
	
	public function getSurname(){
		return $this->surname;
	}
	
	public function setStreet($street){
		$this->street = $street;
		return $this;
	}
	
	public function getStreet(){
		return $this->street;
	}
	
	public function setNumber($number){
		$this->number = $number;
		return $this;
	}
	
	public function getNumber(){
		return $this->number;
	}
	
	public function setCountry($country){
		$this->country = $country;
		return $this;
	}
	
	public function getCountry(){
		return $this->country;
	}
	
}

