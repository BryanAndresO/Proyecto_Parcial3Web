<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Matriculas Marcas</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	
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
            <i class="bi bi-house-door"></i> Homen
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto"> <!-- Se eliminó me-auto y se dejó solo ms-auto -->
				   <!--
                <li class="nav-item">
                    <a class="nav-link active" href="../Vehiculo/index.php">
                        <i class="bi bi-car-front"></i> CRUD Vehículo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
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
	-->	<li class="nav-item">
	<div class="col-lg-4 d-flex justify-content-end" >
							<a href="../logout.php" class="btn btn-danger btn-sm" >
							<i class="fas fa-sign-out-alt" style="transform: scaleX(-1);"></i> Cerrar sesión

							</a>
							</div>

					</li>
            </ul> <!-- Se cerró correctamente el <ul> -->
        </div>
    </div>
</nav>
	<div class="container pt-2" >
		<div></div>
	<?php
		require_once("constantes.php");
		include_once("class/class.marca.php");
		
		$cn = conectar();
		$v = new marca($cn);
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $v->delete_marca($id);
			}elseif($op == "det"){
				echo $v->get_detail_marca($id);
			}elseif($op == "new"){
				echo '<div class="col-lg-3 col-ms-3"></div>';
				echo $v->get_form();
			}elseif($op == "act"){
				echo '<div class="col-lg-3 col-ms-3"></div>';
				echo $v->get_form($id);
			}
			
       // PARTE III	
		}else{
			   
				/*echo "<br>PETICION POST <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>";*/
		      
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_marca();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_marca();
			}else{
				echo $v->get_list();
			}	
		}
		
	//*******************************************************
		function conectar(){
			// echo "<br> CONEXIÓN A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER, USER, PASS, BD);
			
			if ($c->connect_errno) {
				die("Error de conexión: " . $c->connect_errno . ", " . $c->connect_error);
			} else {
				// echo "La conexión tuvo éxito .......<br><br>";
			}
			
			$c->set_charset("utf8");
			return $c;
		}

	//**********************************************************	

		
	?>	

	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

