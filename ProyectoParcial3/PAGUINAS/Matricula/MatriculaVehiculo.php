<?php

class MatriculaVehiculo {
    /* Atributos */
    public $placa;
    public $idvehiculo;
    public $datos = [];

    /* Constructor */
    public function __construct($placa, $idvehiculo) {
        $this->placa = $placa;
        $this->idvehiculo = $idvehiculo;
    }

    /* Métodos Getters */
    public function getvehiculo() {
        return $this->placa;
    }

    public function getPlaca() {
        return $this->placa;
    }

    public function getDatos() {
        return $this->datos;
    }

    /* Método para agregar datos al array */
    public function agregarDato($id, $fecha, $agencia, $anio) {
        $this->datos[] = [
            'id' => $id,
            'fecha' => $fecha,
            'agencia' => $agencia,
            'anio' => $anio
        ];
    }
}

?>
