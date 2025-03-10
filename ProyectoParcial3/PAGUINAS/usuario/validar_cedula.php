<?php
require_once 'class/class.usuario.php'; // Asegúrate de incluir la clase para la conexión a la base de datos

$cn = new mysqli("localhost", "root", "", "matriculacionfinal"); // Cambia estos valores según tu configuración
$usuario = new usuario1($cn);

if (isset($_POST['cedula'])) {
    $cedula = $cn->real_escape_string($_POST['cedula']);
    
    // Validar que la cédula sea un número entero de 10 dígitos
    if (!preg_match('/^\d{10}$/', $cedula)) {
        echo "<span style='color:red;'>La cédula debe ser un número entero de 10 dígitos.</span>";
        exit; // Salir si la validación falla
    }

    $sql = "SELECT * FROM persona WHERE cedula = '$cedula'";
    $result = $cn->query($sql);

    if ($result->num_rows > 0) {
        echo "<span style='color:red;'>Cédula existente</span>";
    } else {
        echo "<span style='color:green;'>Cédula correcta</span>";
    }
} else {
    echo "<span style='color:red;'>Cédula no proporcionada</span>";
}
?> 
