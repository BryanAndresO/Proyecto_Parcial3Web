<?php
require_once("constantes.php");
include_once("class/class.usuario.php");
require_once 'class/class.persona.php'; // Incluir la clase Persona
require_once '../../Usuario.php';
session_start();

$cn = conectar();
$v = new usuario1($cn);

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriculas Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/MisEstilos.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: rgb(241, 207, 57);">
    <div class="container-fluid">
        <a class="navbar-brand ps-5" href="../index.php">
            <i class="bi bi-house-door"></i> Home
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <div class="col-lg-4 d-flex justify-content-end">
                    <a href="../logout.php" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container pt-2">
';

// Verifica si se pasa el parámetro 'd' en la URL para manejar diferentes acciones
if (isset($_GET['d'])) {
    $dato = base64_decode($_GET['d']);
    $tmp = explode("/", $dato);
    $op = $tmp[0];
    $id = $tmp[1];

    if ($op == "del") {
        $html .= $v->delete_vehiculo($id);
    } elseif ($op == "det") {
        $html .= $v->get_detail_vehiculo($id);
    } elseif ($op == "new") {
        $html .= '<div class="container">';
        $html .= $v->get_form();
        $html .= '</div>';
    } elseif ($op == "act") {
        $html .= '<div class="container pt-2" style="margin-left: 25%;">';
        $html .= $v->get_form($id);
        $html .= '</div>';
    }
} else {
    if (isset($_POST['Guardar']) && $_POST['op'] == "new") {
        $v->save_vehiculo();
    } elseif (isset($_POST['Guardar']) && $_POST['op'] == "update") {
        $v->update_vehiculo();
    } elseif (isset($_POST['GuardarPersona'])) {
        // Procesar el formulario de persona
        $persona = new Persona($cn);
        $persona->save_persona();
    } else {
        $html .= $v->get_list();
    }
}

$html .= '
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
';

echo $html;
echo "</form>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Función para conectar a la base de datos
function conectar() {
    $c = new mysqli(SERVER, USER, PASS, BD);
    if ($c->connect_errno) {
        die("Error de conexión: " . $c->connect_errno . ", " . $c->connect_error);
    }
    $c->set_charset("utf8");
    return $c;
}
?>