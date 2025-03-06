<?php
class Persona {
    private $idChofer;
    private $idVehiculo;
    private $nombre;
    private $apellido;
    private $cedula;
    private $idUsuario;
    private $puntosLicencia;
    private $con;

    function __construct($cn) {
        $this->con = $cn;
    }
    public function save_persona() {
        // Verificar si los campos del formulario de persona están presentes
        if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['idUsuario'], $_POST['idVehiculo'], $_POST['puntosLicencia'])) {
            // Escapar los valores para prevenir inyección SQL
            $nombre = $this->con->real_escape_string($_POST['nombre']);
            $apellido = $this->con->real_escape_string($_POST['apellido']);
            $cedula = $this->con->real_escape_string($_POST['cedula']);
            $idUsuario = intval($_POST['idUsuario']);
            $idVehiculo = intval($_POST['idVehiculo']);
            $puntosLicencia = intval($_POST['puntosLicencia']);
    
            // Construir la consulta SQL
            $sql = "INSERT INTO persona (NOMBRE, APELLIDO, CEDULA, ID_USUARIO, ID_VEHICULO, PUNTOS_LICENCIA) 
                    VALUES ('$nombre', '$apellido', '$cedula', '$idUsuario', '$idVehiculo', '$puntosLicencia')";
    
            // Imprimir la consulta SQL para depuración
            echo "Consulta SQL: " . $sql . "<br>";
    
            // Ejecutar la consulta
            if ($this->con->query($sql)) {
                echo $this->_message_ok("guardó");
            } else {
                // Mostrar el error de la base de datos
                echo $this->_message_error("guardar: " . $this->con->error);
            }
        } else {
            echo $this->_message_error("faltan datos en el formulario de persona");
        }
    }


    // Métodos auxiliares para mensajes
    private function _message_ok($action) {
        return "<div class='alert alert-success'>Registro $action correctamente.</div>";
    }

    private function _message_error($action) {
        return "<div class='alert alert-danger'>Error $action el registro.</div>";
    }
}
?>