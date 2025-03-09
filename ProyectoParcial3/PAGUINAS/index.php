<?php

// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor no es local
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$database = "matriculacionfinal"; // Nombre de la base de datos

// Crear conexión
$cn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($cn->connect_error) {
    die("Conexión fallida: " . $cn->connect_error);
}


require_once("Matricula/MatriculaVehiculo.php");
require_once("Vehiculo/MultasVehiculo.php");
require_once '../Usuario.php';
require_once 'Vehiculo/class/class.vehiculo.php'; // Asegúrate de incluir la clase vehiculo
session_start(); // Inicia la sesión

if (isset($_SESSION['listaNote'])) {
    if ($_SESSION['listaNote'] instanceof Usuario) {
        $roles_id = $_SESSION['listaNote']->roles_id;
    } else {
        echo "Error: El objeto en la sesión no es de tipo Usuario.";
    }
} else {
    echo "No hay usuario en la sesión.";
}

// Definir etiquetas habilitadas por rol
$menuOpciones = '';
$Botones = '';
$Rol = '';
$modalHtml = ''; // Variable para almacenar el HTML del modal

if ($_SESSION['listaNote']->roles_id == 6) { 
  $menuOpciones .= '<li><a href="Vehiculo/index.php">CRUD_Vehiculo</a></li>';
  $Botones .= '<p><a href="Vehiculo/index.php"><button class="boton btn-info"><i class="fas fa-car"></i>Matricular-Multar</button></a></p>';
  $Rol = 'AGENTE';
} 
if ($_SESSION['listaNote']->roles_id == 7) { 
    $menuOpciones .= '<li><a href="Marca-Crud/index.php">CRUD_Marca</a></li>';
    $menuOpciones .= '<li><a href="Vehiculo/index.php">CRUD_Vehiculo</a></li>';
    $menuOpciones .= '<li><a href="agencia/index.php">CRUD_Agencia</a></li>';
    $Rol = 'ADM';
} 
if ($_SESSION['listaNote']->roles_id == 8) { 
    $Botones .= '<p><a href="set_session.php?tipo=Vehiculo"><button class="butoon tn-block ">Agregar VEHICULO</button></a></p>';
    $Botones .= '<p><a href="set_session.php?tipo=Agente"><button class="butoon tn-block ">Agregar AGENTE</button></a></p>';
    $Rol = 'SUPERADM';
}

if ($_SESSION['listaNote']->roles_id == 9 ) { 
  $Botones .= '<p><a href="Vehiculo/index.php"><button class="boton">Matricula</button></a></p>';
  $Rol = 'Vehiculo';
    // Llamar a la función get_multar2 para obtener el modal
    $vehiculo = new vehiculo($cn); // Asegúrate de pasar la conexión a la base de datos ($con)
    $modalHtml = $vehiculo->get_multar2($_SESSION['listaNote']->username); // Obtener el HTML del modal
  
}

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<title>Matricula</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/MisEstilos.css">
<style>
/* Estilos adicionales (si los hay) */
</style>
</head>
<body>';

// Agregar el modal al HTML solo si roles_id == 9
$html .= $modalHtml;

$html .= '
<div class="container">
  <div class="row">
    <header class="col-lg-12">
      <center><img src="imagenes/img/logo_ESPE.png"height=”170” width=400”></center>
   </header>
  </div>
</div>

<div class="container">
  <div class="col-lg-3 d-flex align-items-center ml-auto" style="margin-left: 1.5%;">
    <i class="fas fa-user-circle fa-2x mr-3"></i>
    <span class="font-weight-bold">User:'.$_SESSION['listaNote']->username.'</span> - 
    <span class="text-muted">Rol: '.$Rol.'</span>
  </div>
  <div class="col-lg-7 d-flex align-items-center ml-auto" style="margin-left: 1.5%;"></div>
  <div class="col-lg-12 d-flex justify-content-end" >
    <a href="logout.php" class="btn btn-danger btn-sm" >
      <i class="fas fa-sign-out-alt"></i> Cerrar sesión
    </a>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="navegacion col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <nav>
          <ul id="menu1">
            ' . $menuOpciones . '
          </ul>
      </nav>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 color1 pd-0 ">
      ' . $Botones . '
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 color5">
      <h2 style="color: white;">Aplicacion de tecnologias WEB</h2>
      <p style="color: black;">Estudiantes: Carrillo Luis - Ortiz Brayan - Gualotuña Paul </p>
      <p style="color: black;">NRC:  </p>
      <p style="color: black;">Fecha: 11/03/2025</p>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 color4 responsive" style="width: 360px; background-color: white;">
      <video width="100%" controls autoplay muted>
        <source src="../video/Video.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
      </video>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <footer>
      <div class="navegacion">
        <nav>
            <ul id="menu1">
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
      </div>
    </footer>
  </div>
</div>



</body>
</html>';

echo $html;

echo "<br>VARIABLE SESSION: <br>";
echo "<pre>";
    print_r($_SESSION);
echo "</pre>";

?>

