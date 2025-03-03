<?php

class Usuario{
	/* Atributos*/

	public $id;
	public $Nombre;
	public $Apellido;
	public $Cedula;
	public $idvehiculo;
	public $username;
	public $password;
	public $roles_id;
	public $placa;
	public $puntos;
	
	/*Constructor*/
	public function __construct($id,$username, $password,$roles_id,$Nombre,$Apellido,$Cedula,$puntos,$placa,$idvehiculo){
		$this->id= $id;
		$this->idvehiculo= $idvehiculo;
		$this->username= $username;
		$this->password = $password;
		$this->roles_id = $roles_id;
		$this->puntos = $puntos;
		$this->Nombre = $Nombre;
		$this->Apellido = $Apellido;
		$this->Cedula = $Cedula;
		$this->placa = $placa;
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

	public function getPuntos(){
		return $this->puntos;
	}
	public function getNombre(){
		return $this->Nombre;
	}
	public function getApellido(){
		return $this->Apellido;
	}
	public function getCedula(){
		return $this->Cedula;
	}
	public function getPlaca(){
		return $this->placa;
	}

}
?>