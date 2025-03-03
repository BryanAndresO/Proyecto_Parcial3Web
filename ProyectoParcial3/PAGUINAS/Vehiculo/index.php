<?php

require_once("MatriculaVehiculo.php");
require_once("MultasVehiculo.php");
require_once '../../Usuario.php';

function cargarVehiculosMatriculados($conexion) {
    $sql = "SELECT 
                m.id AS matricula_id,
                v.placa,
                v.id AS vehiculo_id,
                m.fecha AS fecha_matricula,
                a.descripcion AS agencia_nombre,
                m.anio AS anio_matricula
            FROM matriculacionfinal.matricula m
            INNER JOIN matriculacionfinal.vehiculo v ON m.vehiculo = v.id
            INNER JOIN matriculacionfinal.agencia a ON m.agencia = a.id";

    $vehiculos = []; 

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $placa = $row['placa'];
            $idvehiculo = $row['vehiculo_id'];

            if (!isset($vehiculos[$placa])) {
                $vehiculos[$placa] = new MatriculaVehiculo($placa, $idvehiculo);
            }

            $vehiculos[$placa]->agregarDato(
                $row['matricula_id'],
                $row['fecha_matricula'],
                $row['agencia_nombre'],
                $row['anio_matricula']
            );
        }

        $stmt->close();
    }

    return array_values($vehiculos);
}


$conexion = new mysqli("localhost", "root", "", "matriculacionfinal");
if ($conexion->connect_errno) {
die("Error de conexión: " . $conexion->connect_error);
}




/*echo "<pre>";
print_r($vehiculosMatriculados);
echo "</pre>";*/




////////////////MUlTAS POR Vehiculo ////////////////////////

function cargarVehiculosConMultas($conexion) {
    $sql = "SELECT 
                v.id AS ID_VEHICULO,
                v.placa,
                p.ID_CHOFER,
                p.APELLIDO,
                p.NOMBRE,
                p.CEDULA,
                p.PUNTOS_LICENCIA,
                m.ID_USUARIO,
                m.ID_MULTA,
                m.FECHA,
                tm.CATEGORIA,
                tm.DESCRIPCION,
                tm.PUNTOS
            FROM 
                matriculacionfinal.vehiculo v
            JOIN 
                matriculacionfinal.multas m ON v.id = m.ID_VEHICULO
            JOIN 
                matriculacionfinal.tipo_multas tm ON m.ID_TIPO = tm.ID_TIPO
			JOIN 
				matriculacionfinal.persona p on p.ID_CHOFER=v.id_persona
			
            ORDER BY 
                v.id, m.FECHA";

    $vehiculosM = [];

    if ($stmt1 = $conexion->prepare($sql)) {
        $stmt1->execute();
        $result = $stmt1->get_result();

        while ($row = $result->fetch_assoc()) {
            $idVehiculo = $row['ID_VEHICULO'];
            $idpersona = $row['ID_CHOFER'];
            $apellido = $row['APELLIDO'];
            $nombre = $row['NOMBRE'];
            $cedula = $row['CEDULA'];
            $puntosLicencia = $row['PUNTOS_LICENCIA'];
            $placa = $row['placa'];
            $idUsuario = $row['ID_USUARIO'];
           
            // Si el vehículo no está en el array, lo creamos
            if (!isset($vehiculosM[$idVehiculo])) {
                $vehiculosM[$idVehiculo] = new MultasVehiculo ($idVehiculo,$placa,$idpersona,$apellido,$nombre,$cedula,$idUsuario,$puntosLicencia);
            }

            // Agregar la multa al vehículo correspondiente
            $vehiculosM[$idVehiculo]->agregarDato(
                $row['ID_MULTA'],
                $row['FECHA'],
                $row['CATEGORIA'],
                $row['DESCRIPCION'],
                $row['PUNTOS']
            );
        }

        $stmt1->close();
    }

    return array_values($vehiculosM); // Convertimos el array asociativo a un array indexado
}


$vehiculosMatriculados = cargarVehiculosMatriculados($conexion);
$vehiculosConMultas = cargarVehiculosConMultas($conexion);
$conexion->close();
/*echo "<pre>";
print_r($vehiculosConMultas);
echo "</pre>";*/


session_start();
//$_SESSION['Matriculados']=$vehiculosMatriculados ;


$_SESSION["Matriculados"] =$vehiculosMatriculados ;
$_SESSION["Multados"] =$vehiculosConMultas;

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

                <li class="nav-item">
                    <a class="nav-link disabled">
                        <i class="bi bi-x-circle"></i> Disabled
                    </a>
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
    include_once("class/class.vehiculo.php");

    $cn = conectar();
    $v = new vehiculo($cn);

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
        }elseif($op == "addmat"){
            echo '<div class="container pt-2"  >';
            echo $v->matricular($id);
            echo '</div>';
        }elseif($op == "addmulta"){
            echo '<div class="container pt-2"  >';
            echo $v->multar($id);
            echo '</div>';
        }
    } else {
        if(isset($_POST['Guardar']) && $_POST['op']=="new"){
            $v->save_vehiculo();
        } elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
            $v->update_vehiculo();
        } elseif(isset($_POST['Guardar']) && $_POST['op']=="mat"){
            $v->save_matricula();
        }elseif(isset($_POST['Guardar']) && $_POST['op']=="multa"){
            $v->save_multa();
        }
        else {
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

</body>
</html>
