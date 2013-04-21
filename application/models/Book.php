<?php

/**
 * Třída mapující záznam tabulky book na objekt Book.
 * @author Magda Kutišová
 *
 */
class Application_Model_Book
{

	protected $idBook;
	protected $name;
	protected $author;
	protected $description;
	protected $price;
	protected $stock;
	protected $image;
	
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
			throw new Exception('Neplatný atribut knihy.');
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
			throw new Exception('Neplatný atribut knihy.');
		}
		return $this->$method();
	}
	
	/**
	 * Nastavení atributů třídy
	 * @param array $options atributy třídy
	 * @return Application_Model_Book instance třídy s nastavenými atributy
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
	
	public function setIdBook($idBook){
		$this->idBook = $idBook;
		return $this;
	}
	
	public function getIdBook(){
		return $this->idBook;
	}
	
	public function setName($name){
		$this->name = $name;
		return $this;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setAuthor($author){
		$this->author = $author;
		return $this;
	}
	
	public function getAuthor(){
		return $this->author;
	}
	
	public function setDescription($description){
		$this->description = $description;
		return $this;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setPrice($price){
		$this->price = $price;
		return $this;
	}
	
	public function getPrice(){
		return $this->price;
	}
	
	public function setStock($stock){
 		$this->stock = $stock;
 		return $this;
	}
	
	public function getStock(){
		return $this->stock;
	}
	
	public function setImage($image){
		$this->image = $image;
		return $this;
	}
	
	public function getImage(){
		return $this->image;
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
		$this->idBook = (isset($data['idBook'])) ? $data['idBook'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->author = (isset($data['author'])) ? $data['author'] : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->price = (isset($data['price'])) ? $data['price'] : null;
		$this->stock = (isset($data['stock'])) ? $data['stock'] : null;
		$this->image = (isset($data['image'])) ? $data['image'] : null;
	}
		
}

