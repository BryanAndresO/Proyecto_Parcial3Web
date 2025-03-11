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

    // Algoritmo de validación de cédula ecuatoriana
    function validarCedulaEcuatoriana($cedula) {
        // Verificar que los dos primeros dígitos correspondan a una provincia válida (01 a 24)
        $provincia = substr($cedula, 0, 2);
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        // Verificar el dígito verificador
        $digitos = str_split($cedula); // Convertir la cédula en un array de dígitos
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2]; // Coeficientes para el cálculo
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = $digitos[$i] * $coeficientes[$i];
            $suma += ($valor >= 10) ? $valor - 9 : $valor;
        }

        $digitoVerificador = (10 - ($suma % 10)) % 10; // Calcular el dígito verificador
        if ($digitos[9] != $digitoVerificador) {
            return false;
        }

        return true;
    }

    // Validar la cédula ecuatoriana
   /* if (!validarCedulaEcuatoriana($cedula)) {
        echo "<span style='color:red;'>Cédula no válida.</span>";
        exit; // Salir si la validación falla
    }*/

    // Verificar si la cédula ya existe en la base de datos
    $sql = "SELECT * FROM persona WHERE cedula = '$cedula'";
    $result = $cn->query($sql);

    if ($result->num_rows > 0) {
        echo "<span style='color:red;'>Cédula existente.</span>";
    } else {
        echo "<span style='color:green;'>Cédula correcta</span>";
    }
} else {
    echo "<span style='color:red;'>Cédula no proporcionada</span>";
}
?>
