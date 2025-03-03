<html>
  <head>
    <meta charset="utf-8">
    <title>Sesiones en PHP</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>
  <body style="background-color: blue;">



  
<?php
// Incluir la clase Usuario
require_once 'Usuario.php';
session_start();
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Ajusta según tu configuración
$password = ""; // Ajusta según tu configuración
$database = "matriculacionfinal";

$conn = new mysqli($servername, $username, $password, $database);


// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}



// Consulta SQL con JOIN
$sql = "SELECT 
	u.id,
    u.username, 
    u.password, 
    u.roles_id, 
    p.NOMBRE, 
    p.APELLIDO, 
    p.CEDULA, 
    p.PUNTOS_LICENCIA,
    v.placa, 
    v.id AS vehiculo_id
FROM  matriculacionfinal.usuarios u
LEFT JOIN  matriculacionfinal.persona p ON u.id = p.ID_USUARIO
LEFT JOIN  matriculacionfinal.vehiculo v ON p.ID_CHOFER = v.id_persona;";

$result = $conn->query($sql);

// Array para almacenar objetos de tipo Usuario
$usuarios = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Crear un objeto Usuario con los datos obtenidos
        $usuario = new Usuario($row['id'], $row['username'], $row['password'], $row['roles_id'],$row['NOMBRE'],$row['APELLIDO'],$row['CEDULA'],$row['PUNTOS_LICENCIA'],$row['placa'],$row['vehiculo_id']);
        $usuarios[] = $usuario;
    }
}

// Cerrar conexiones
$conn->close();
// Guardar en sesión

$_SESSION['listaNote'] = $usuarios;


	/*
	echo '<form action="validar.php" method="POST">';
		echo '<h2>Formulario de login</h2>';
		//echo '<input type="text" placeholder="&#128272; Usuario" name="usuario">';

        echo '<input type="text" placeholder="&#128272; Contraseña" name="usuario">';	
		echo '<input type="password" placeholder="&#128272; Contraseña" name="clave">';	
		echo '<input type="submit" value="LOGIN">';
	echo "</form>";*/

    $hml='
    <div class="container">
           
            <div class="modal fade" id="miModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="d-flex align-items-center justify-content-center">
                            <img src="PAGUINAS/imagenes/img/log-ant.jpg" alt="Logo" class="ms-2 " style="height: 60px; margin-left: 2.5%;">
                            <h2 class="modal-title text-center mb-0">AMT</h2>
                            <h3 class="modal-title text-center mb-0">AGENTE(Rol=1)/ADM(Rol=2)/SUPERADM(Rol=3)</h3>
                        </div>

                        <div class="modal-body">
                            <form action="validar.php" method="POST">
                                <div class="form-group">
                                    <label for="usuario">Usuario</label>
                                    <input type="text" placeholder="usuario" class="form-control" name="usuario">
                                </div>
                                <div class="form-group">
                                    <label for="contraseña">Contraseña</label>
                                    <input type="password" placeholder="contraseña" class="form-control"  name="clave">
                                </div>

                                <div class="modal-footer">
                                <button class="btn btn-primary" type="submit" value="LOGIN" >LOGIN</button> 
                                        
                                </div>
                            </form>
                        </div>

          

                    </div>
                </div>
            </div>

        </div>
        

    ';

    echo $hml;

    ;
   
?>

<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.min.js"><\/script>')</script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/main.js"></script>
  </body>
</html>

 

