<?php
require_once 'Usuario.php';
	session_start();
	
		/*	echo "<br>VARIABLE SESSION: <br>";
			echo "<pre>";
				print_r($_SESSION);
			echo "</pre>";
    
			echo "<br>VARIABLE POST: <br>";
			echo "<pre>";
				print_r($_POST);
			echo "</pre>";*/
				
    $usuario = $_POST['usuario'];
	$clave = $_POST['clave'];   	
    	

	

	//Conectar a la base de datos
	$conexion = mysqli_connect("localhost", "root", "", "matriculacionfinal");
	$consulta = "SELECT * FROM usuarios WHERE username = '$usuario' and password = '$clave'";
	$resultado = mysqli_query($conexion, $consulta);

	$filas = mysqli_num_rows($resultado); //0 si no coincide, 1 o + si concidio
	
	//echo $_SESSION['listaNote']->roles_id;
	
	if($filas>0){

		$row = mysqli_fetch_assoc($resultado); // Obtener datos del usuario
		$buscarUsername = $usuario;
		if (isset($_SESSION['listaNote']) && is_array($_SESSION['listaNote'])) {
			$usuarios = $_SESSION['listaNote'];
			$usuarioEncontrado = null;
		
			foreach ($usuarios as $usuario) {
				if ($usuario->username === $buscarUsername) {
					$usuarioEncontrado = $usuario;
					break; // Salir del bucle al encontrarlo
				}
			}
		
			if ($usuarioEncontrado) {

				echo "ID Vehículo: " . ($usuarioEncontrado->idvehiculo ?? "No tiene") . "<br>";
				    // Crear el objeto usuario y guardarlo en la sesión
					$_SESSION["listaNote"] = new usuario($row['id'],$row['username'], $row['password'], $row['roles_id'],$usuarioEncontrado->Nombre,$usuarioEncontrado->Apellido,$usuarioEncontrado->Cedula,$usuarioEncontrado->puntos,$usuarioEncontrado->placa,$usuarioEncontrado->idvehiculo);
	
			} else {
				echo "No se encontró un usuario con username: $buscarUsername";
			}
		} else {
			echo "No hay usuarios en la sesión.";
		}


    // Redirigir
    //header("location: verNotebook.php?op=" . $row['username']);
		//echo "<br>AUTENTICACION EXISTOSA<br>";
		header("location:PAGUINAS/index.php?op=".$row['roles_id']);
	}
	else{		
		echo "<br>Error en la autentificación";
		header("location:ErrorAutentificacion.php");
	}

				

	mysqli_free_result($resultado);
	mysqli_close($conexion);
?>