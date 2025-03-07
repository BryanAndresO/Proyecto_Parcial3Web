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
        if (isset($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], $_POST['idUsuario'])) {
            // Escapar los valores para prevenir inyección SQL
            $this->nombre = $this->con->real_escape_string($_POST['nombre']);
            $this->apellido = $this->con->real_escape_string($_POST['apellido']);
            $this->cedula = $this->con->real_escape_string($_POST['cedula']);
            $this->idUsuario = intval($_POST['idUsuario']);
            
            // Obtener el rol del usuario desde la sesión
            $rol = isset($_SESSION['BOTON']) ? $_SESSION['BOTON'] : 0;
            
            // Establecer valores según el rol
            if ($rol == 6) { // Agente de tránsito
                // Para agentes, usamos 0 para ID_VEHICULO en lugar de NULL
                $this->idVehiculo = 0; // Cambio aquí: usar 0 en lugar de NULL
                $this->puntosLicencia = 0; // No tiene puntos de licencia
            } else { // Rol 9 (usuario vehículo)
                $this->idVehiculo = isset($_POST['idVehiculo']) ? intval($_POST['idVehiculo']) : 0;
                $this->puntosLicencia = isset($_POST['puntosLicencia']) ? intval($_POST['puntosLicencia']) : 20;
            }
            
            // Obtener el último ID_CHOFER para generar el siguiente
            $sql_last_id = "SELECT MAX(ID_CHOFER) as max_id FROM persona";
            $result = $this->con->query($sql_last_id);
            $row = $result->fetch_assoc();
            $this->idChofer = ($row['max_id'] === null) ? 1 : $row['max_id'] + 1; // Manejar el caso de tabla vacía
            
            // Construir una única consulta SQL que funcione para ambos roles
            $sql = "INSERT INTO persona (ID_CHOFER, ID_VEHICULO, NOMBRE, APELLIDO, CEDULA, ID_USUARIO, PUNTOS_LICENCIA) 
                    VALUES ($this->idChofer, $this->idVehiculo, '$this->nombre', '$this->apellido', '$this->cedula', $this->idUsuario, $this->puntosLicencia)";
            
            if ($this->con->query($sql)) {
                echo $this->_message_ok("guardó persona");
                // Redirigir a la página principal después de 2 segundos
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 2000);
                      </script>";
            } else {
                echo $this->_message_error("al guardar");
                echo "Error SQL: " . $this->con->error; // Muestra el error específico para depuración
            }
        } else {
            echo $this->_message_error("faltan datos del formulario");
        }
    }
    // Métodos auxiliares para mensajes
    private function _message_ok($accion) {
        return "<div class='alert alert-success'>Se $accion con éxito.</div>
                <a href='index.php' class='btn btn-primary'>Regresar</a>";
    }
    
    private function _message_error($action) {
        return "<div class='alert alert-danger'>Error $action el registro.</div>";
    }

}
?>