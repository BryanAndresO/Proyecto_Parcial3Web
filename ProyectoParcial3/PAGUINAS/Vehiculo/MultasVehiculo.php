<?php

class MultasVehiculo{
    /* Atributos */
    public $idvehiculo;
    public $placa;
    public $idpersona;
    public $apellido;
    public $nombre;
    public $cedula;
    public $puntosLicencia;
    public $idusuario;

    public $datos = [];

    /* Constructor */
    public function __construct($idvehiculo,$placa,$idpersona,$apellido,$nombre,$cedula,$idusuario,$puntosLicencia){
        $this->placa = $placa;
        $this->idvehiculo = $idvehiculo;
        $this->idusuario = $idusuario;
        $this->idpersona = $idpersona;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->puntosLicencia = $puntosLicencia;
    	$this->idusuario = $idusuario;
        $this->placa = $placa;

    }

    /* Métodos Getters */
    public function getvehiculo() {
        return $this->idvehiculo;
    }

    public function getPlaca() {
        return $this->placa;
    }
    public function getIdUsuario() {
        return $this->idusuario;
    }

    public function getDatos() {
        return $this->datos;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getCedula() {
        return $this->cedula;
    }


    /* Método para agregar datos al array */
    public function agregarDato($idmulta,$Mfecha,$Mcategoria, $Mdescripcion, $Mpuntos) {
        $this->datos[] = [
            'idmulta' => $idmulta,
            'Mfecha' => $Mfecha,
            'Mcategoria' => $Mcategoria,
            'Mdescripcion' => $Mdescripcion,
            'Mpuntos' => $Mpuntos
        ];
    }
}

?>