<?php

class Usuario{
	/* Atributos*/

	public $id;
	public $idvehiculo;
	public $username;
	public $password;
	public $roles_id;
	
	/*Constructor*/
	public function __construct($id, $idvehiculo,$username, $password,$roles_id){
		$this->id= $id;
		$this->idvehiculo= $idvehiculo;
		$this->username= $username;
		$this->password = $password;
		$this->roles_id = $roles_id;
	}
	public function getId(){
		return $this->id;
	}
	public function getUsername(){
		return $this->username;
	}
	public function getPassword(){
		return $this->password;
	}

	public function getRoles_id(){
		return $this->roles_id;
	}
	
	public function getidvehiculo(){
		return $this->idvehiculo;
	}
}
?>