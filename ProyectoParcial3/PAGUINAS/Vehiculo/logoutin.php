<?php
session_start();
$_SESSION = array(); // Vacía todas las variables de sesión
header("Location: ../../validar.php"); // Redirige a la página de inicio
exit();
?>