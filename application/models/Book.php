<?php

class Application_Model_Book
{

	protected $idBook;
	protected $name;
	protected $author;
	protected $description;
	protected $price;
	protected $stock;
	
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
			throw new Exception('Neplatný atribut knihy.');
		}
		$this->$method($value);
	}
	
	/*********************
	 * Implementace magic metody __get.
	 */
	public function __get($name){
		$method = 'get' . $name;
		if(('mapper' == $name) || !method_exists($this, $method)){
			throw new Exception('Neplatný atribut knihy.');
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
		
}

