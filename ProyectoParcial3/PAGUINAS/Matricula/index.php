<?php
require_once("MatriculaVehiculo.php");
require_once '../../Usuario.php';


function cargarVehiculosMatriculados($conexion) {
    $sql = "SELECT 
                m.id AS matricula_id,
                v.placa,
                v.id,
                m.fecha AS fecha_matricula,
                a.descripcion AS agencia_nombre,
                m.anio AS anio_matricula
            FROM matriculacionfinal.matricula m
            INNER JOIN matriculacionfinal.vehiculo v ON m.vehiculo = v.id
            INNER JOIN matriculacionfinal.agencia a ON m.agencia = a.id";

    $vehiculos = []; // Array de objetos MatriculaVehiculo

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $placa = $row['placa'];
            $idvehiculo = $row['id'];
            // Si el vehículo ya existe en el array, lo usamos; si no, lo creamos
            if (!isset($vehiculos[$placa])) {
                $vehiculos[$placa] = new MatriculaVehiculo($placa, $idvehiculo);
            }

            // Agregar la matrícula al objeto correspondiente
            $vehiculos[$placa]->agregarDato(
                $row['matricula_id'],
                $row['fecha_matricula'],
                $row['agencia_nombre'],
                $row['anio_matricula']
            );
        }

        $stmt->close();
    }

    return array_values($vehiculos); // Convertimos el array asociativo a un array indexado
}


$conexion = new mysqli("localhost", "root", "", "matriculacionfinal");
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$vehiculosMatriculados = cargarVehiculosMatriculados($conexion);

    /*echo "<pre>";
    print_r($vehiculosMatriculados);
    echo "</pre>";*/


$conexion->close();



session_start();
//$_SESSION['Matriculados']=$vehiculosMatriculados ;


$_SESSION["Matriculados"] =$vehiculosMatriculados ;

?>

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

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto"> <!-- Se eliminó me-auto y se dejó solo ms-auto -->
                  <!--
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="bi bi-car-front"></i> CRUD Vehículo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="../Marca-Crud/index.php">
                        <i class="bi bi-tags"></i> CRUD Marca
                    </a>
                </li>
				           
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-link"></i> Link
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list"></i> Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-check-circle"></i> Action</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-question-circle"></i> Something else here</a></li>
                    </ul>
                </li>

            
-->

<li class="nav-item">
                      <div class="col-lg-4 d-flex justify-content-end" >
                        <a href="../logout.php" class="btn btn-danger btn-sm" >
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </a>
                        </div>

                </li>
            </ul> <!-- Se cerró correctamente el <ul> -->
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container pt-2">
    <?php
    require_once("constantes.php");
    include_once("class/class.matricula.php");

    $cn = conectar();
    $v = new matricula($cn);

    if(isset($_GET['d'])){
        $dato = base64_decode($_GET['d']);
        $tmp = explode("/", $dato);
        $op = $tmp[0];
        $id = $tmp[1];

        if($op == "del"){
            echo $v->delete_vehiculo($id);
        } elseif($op == "det"){
            echo $v->get_detail_vehiculo($id);
        } elseif($op == "new"){
            echo '<div class="container">';
            echo $v->get_form();
            echo '</div>';
        } elseif($op == "act"){
            echo '<div class="container pt-2" style="margin-left: 25%;" >';
            echo $v->get_form($id);
            echo '</div>';
        }elseif($op == "mat"){
            echo '<div class="container pt-2"  >';
            echo $v->get_matricula($id);
            echo '</div>';
        }
    } else {
        if(isset($_POST['Guardar']) && $_POST['op']=="new"){
            $v->save_vehiculo();
        } elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
            $v->update_vehiculo();
        } else {
            echo $v->get_list();
        }
    }

    function conectar(){
        $c = new mysqli(SERVER, USER, PASS, BD);
        if ($c->connect_errno) {
            die("Error de conexión: " . $c->connect_errno . ", " . $c->connect_error);
        }
        $c->set_charset("utf8");
        return $c;
    }
   
    echo "</form>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";					

    ?>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let hoy = new Date();
        let fechaFormateada = hoy.toISOString().split('T')[0]; // Formato YYYY-MM-DD
        document.getElementById("fecha").value = fechaFormateada;
    });
</script>
</body>
</html>
