<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehículo</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php




class vehiculo{
	private $id;
	private $placa;
	private $marca;
	private $motor;
	private $chasis;
	private $combustible;
	private $anio;
	private $color;
	private $foto;
	private $avaluo;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_vehiculo() **************************************************	
	
	public function update_vehiculo(){
		$this->id = $_POST['id'];
		$this->placa = $_POST['placa'];
		$this->motor = $_POST['motor'];
		$this->chasis = $_POST['chasis'];
			
		$this->marca = $_POST['marcaCMB'];
		$this->anio = $_POST['anio'];
		$this->color = $_POST['colorCMB'];
		$this->combustible = $_POST['combustibleRBT'];
		
		
		
		$sql = "UPDATE vehiculo SET placa='$this->placa',
									marca=$this->marca,
									motor='$this->motor',
									chasis='$this->chasis',
									combustible='$this->combustible',
									anio='$this->anio',
									color=$this->color
				WHERE id=$this->id;";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	

//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_vehiculo(){
		
		
		$this->placa = $_POST['placa'];
		$this->motor = $_POST['motor'];
		$this->chasis = $_POST['chasis'];
		$this->avaluo = $_POST['avaluo'];

		
		$this->marca = $_POST['marcaCMB'];
		$this->anio = $_POST['anio'];
		$this->color = $_POST['colorCMB'];
		$this->combustible = $_POST['combustibleRBT'];
		
		 
				echo "<br> FILES <br>";
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";
		     
		
		
		$this->foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		$path = "../../../imagenes/autos/" . $this->foto;
		
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],$path)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
		
		$sql = "INSERT INTO matriculacionfinal.vehiculo VALUES(NULL,
											'$this->placa',
											$this->marca,
											'$this->motor',
											'$this->chasis',
											'$this->combustible',
											$this->anio,
											$this->color,
											'$this->foto',
											$this->avaluo,
											null);";
		echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}								
										
	}

	

///******************************* METODO GUARDAR MATRICULACION **************************************************

	public function save_matricula(){
		$servername = "localhost"; // Cambia esto si tu servidor no es local
		$username = "root"; // Usuario de la base de datos
		$password = ""; // Contraseña de la base de datos
		$database = "matriculacionfinal"; // Nombre de la base de datos
	
		// Crear conexión
		$conn = new mysqli($servername, $username, $password, $database);
	
		// Verificar conexión
		if ($conn->connect_error) {
			die("Conexión fallida: " . $conn->connect_error);
		}
	
		// Obtener datos del formulario
		$vehiculo = intval($_POST['idvehiculo']); // Asegurar que es un número
		$fecha = $_POST['fecha'];
		$agencia = $conn->real_escape_string($_POST['agencia']);
		$anio = intval($_POST['anio']); // Asegurar que es un número
	
		// Validar si el año ya está registrado para el vehículo
		$sql_check = "SELECT COUNT(*) as total FROM matricula WHERE vehiculo = ? AND anio = ?";
		$stmt = $conn->prepare($sql_check);
		$stmt->bind_param("ii", $vehiculo, $anio);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();	// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
	
		if ($row['total'] > 0) {
			echo $this->_message_error("El vehículo ya tiene una matrícula registrada para el año $anio");
		} else {
			// Preparar la consulta de inserción
			$sql = "INSERT INTO matricula (id, fecha, vehiculo, agencia, anio) VALUES (NULL, ?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("sisi", $fecha, $vehiculo, $agencia, $anio);
			
			
			if ($stmt->execute()) {
				echo $this->_message_ok("guardó");
			} else {
				echo $this->_message_error("guardar");
			}
		}
	
		// Cerrar la conexión
		$stmt->close();
		$conn->close();
	}


	///******************************* METODO GUARDAR MULTA **************************************************

public function restar_puntos($idvehiculo,$cedulapersona,$idtipo){

		$servername = "localhost"; // Cambia esto si tu servidor no es local
		$username = "root"; // Usuario de la base de datos
		$password = ""; // Contraseña de la base de datos
		$database = "matriculacionfinal"; // Nombre de la base de datos
	
		// Crear conexión
		$conn = new mysqli($servername, $username, $password, $database);
	
		// Verificar conexión
		if ($conn->connect_error) {
			die("Conexión fallida: " . $conn->connect_error);
		}


	

		//////************ 1. OBTENER PUNTOS DE LA PERSONA ************//////
		$sqlPersona = "SELECT * FROM matriculacionfinal.persona p
						JOIN matriculacionfinal.vehiculo v on v.id= p.ID_VEHICULO
						where v.id=$idvehiculo";


		$resPersona = $this->con->query($sqlPersona);
			
		$rowPersona = $resPersona->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
		$numPersona = $resPersona->num_rows;

        if($numPersona==0){
             $mensaje = "No se encontrado una persona con la cedula".$cedulapersona;
            echo $this->_message_error($mensaje);
        }else{   

			

			$puntosPersona = $rowPersona['PUNTOS_LICENCIA'];
			$idpersona = $rowPersona['ID_CHOFER'];
		}


		//////************ 2. OBTENER PUNTOS DE LA MULTA ************//////
		$sqlMulta = "SELECT PUNTOS FROM matriculacionfinal.tipo_multas where ID_TIPO=$idtipo;";

		$resMulta = $this->con->query($sqlMulta);
		$rowMulta = $resMulta->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
		
		$numMulta = $resMulta->num_rows;
		

		if($numMulta==0){
			$mensaje = "No el tipo de multa ".$idtipo;
			return $this->_message_error($mensaje);
		}else{   

			
			$puntosMulta = $rowMulta['PUNTOS'];

			if($puntosPersona < $puntosMulta){
				return $this->_message_error("No se puede realizar la multa, la persona no tiene suficientes puntos");
			
			}else{
				//////************ 3. RESTAR PUNTOS A LA PERSONA ************//////
				
				$puntosActualizados = $puntosPersona - $puntosMulta;
				$sqlRestar = "UPDATE matriculacionfinal.persona SET PUNTOS_LICENCIA = $puntosActualizados WHERE ID_CHOFER=$idpersona;";
				//echo $sqlRestar;
				if($this->con->query($sqlRestar)){
					return 'Se ha restado los puntos correctamente';
				}else{
					return $this->_message_error("guardar");
				}	
			}
		}
		$conn->close();
		
	}

	public function save_multa(){
		$servername = "localhost"; // Cambia esto si tu servidor no es local
		$username = "root"; // Usuario de la base de datos
		$password = ""; // Contraseña de la base de datos
		$database = "matriculacionfinal"; // Nombre de la base de datos
	
		// Crear conexión
		$conn = new mysqli($servername, $username, $password, $database);
	
		// Verificar conexión
		if ($conn->connect_error) {
			die("Conexión fallida: " . $conn->connect_error);
		}
		


		// Obtener datos del formulario
		$idvehiculo = $_POST['Midvehiculo'];// Asegurar que es un número
		
		$idusuario = $_POST['Midusuario'];
		$cedulapersona = $_POST['Mcedulapersona'];
		$idtipo = $_POST['Mmulta'];
		$fecha = $_POST['Mfecha'];




		$sql = "INSERT INTO multas (ID_MULTA,ID_VEHICULO,ID_USUARIO,ID_TIPO,FECHA) VALUES (NULL,$idvehiculo, 
		
		$idusuario,$idtipo,'$fecha');";

		//echo $sql;
		$this->restar_puntos($idvehiculo,$cedulapersona,$idtipo);
		if($this->con->query($sql)){
			
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}	
	
	

		$conn->close();
	}
	



//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}
	
	
//*************************************** PARTE I ************************************************************
	
	    
	 /*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select class="form-control" name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto){
		$html = '<select class="form-control"  name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($i == $defecto)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto){
		
		$html = '
		<table border=0 align="left">';
		
		//CODIGO NECESARIO EN CASO QUE EL USUARIO NO SE ESCOJA UNA OPCION
		
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				
				if($defecto == NULL){
					// OPCION PARA GRABAR UN NUEVO VEHICULO (id=0)
					$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN VEHICULO EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
	
//************************************* PARTE II ****************************************************	

	public function get_form($id=NULL){
		
		if($id == NULL){
			$this->placa = NULL;
			$this->marca = NULL;
			$this->motor = NULL;
			$this->chasis = NULL;
			$this->combustible = NULL;
			$this->anio = NULL;
			$this->color = NULL;
			$this->foto = NULL;
			$this->avaluo =NULL;
			
			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM vehiculo WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar el vehiculo con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
			/*echo "<br>TUPLA <br>";
				echo "<pre>";
					print_r($row);
				echo "</pre>";*/
			
				$this->placa = $row['placa'];
				$this->marca = $row['marca'];
				$this->motor = $row['motor'];
				$this->chasis = $row['chasis'];
				$this->combustible = $row['combustible'];
				$this->anio = $row['anio'];
				$this->color = $row['color'];
				$this->foto = $row['foto'];
				$this->avaluo = $row['avaluo'];
				
				$flag = "disabled";
				$op = "update";
			}
		}
		
		
		$combustibles = ["Gasolina",
						 "Diesel",
						 "Eléctrico"
						 ];

						 

		$html = '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					
						<th class="text-center bg-dark text-white" scope="col" colspan="2">
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-warning" href="index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto">DATOS VEHÍCULO</span>
							</div>
						</th>
					
				</tr>

				<tr>
					<td>Placa:</td>
					<td><input type="text"  size="6" name="placa" value="' . $this->placa . '" required></td>
				</tr>
				<tr>
					<td>Marca:</td>
					<td>' . $this->_get_combo_db("marca","id","descripcion","marcaCMB",$this->marca) . '</td>
				</tr>
				<tr>
					<td>Motor:</td>
					<td><input type="text" size="15" name="motor" value="' . $this->motor . '" required></td>
				</tr>	
				<tr>
					<td>Chasis:</td>
					<td><input type="text" size="15" name="chasis" value="' . $this->chasis . '" required></td>
				</tr>
				<tr>
					<td>Combustible:</td>
					<td>' . $this->_get_radio($combustibles, "combustibleRBT",$this->combustible) . '</td>
				</tr>
				<tr>
					<td>Año:</td>
					<td>' . $this->_get_combo_anio("anio",1980,$this->anio) . '</td>
				</tr>
				<tr>
					<td>Color:</td>
							<td>' . $this->_get_combo_db("color","id","descripcion","colorCMB",$this->color) . '</td>
				</tr>
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>
				<tr>
					<td>Avalúo:</td>
					<td><input type="text" size="8" name="avaluo" value="' . $this->avaluo . '" ' . $flag . ' required></td>
				</tr>
				<tr>
					
					<th  class="text-center" colspan="2">
						<button class="btn btn-outline-success" type="submit" name="Guardar"  value="GUARDAR">
							<i class="fas fa-save"></i> GUARDAR
						</button>

						<a class="btn btn-outline-danger" href="index.php">
							<i class="fas fa-times"></i> CANCELAR
						</a>
					</th>
					</tr>												
			</table>';
		return $html;
	}
	
	

	public function get_list(){

	

	

		if (isset($_SESSION['listaNote'])) {
			if ($_SESSION['listaNote'] instanceof Usuario) {
				$roles_id = $_SESSION['listaNote']->roles_id;
				//echo "El ID del rol es: " . $roles_id;
			} else {
				echo "Error: El objeto en la sesión no es de tipo Usuario.";
			}
		} else {
			echo "No hay usuario en la sesión.";
		}

		// Definir etiquetas habilitadas por rol
		$menuOpciones = '';
		$Botones = '';
		$BotonesADM = '';
	
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		if ($_SESSION['listaNote']->roles_id == 7) {	
			$BotonesADM ='<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-primary px-5 text-white" href="index.php?d=' . $d_new_final . '"> <i class="bi bi-car-front"></i> Ingresar un nuevo vehiculo</a></th>';
		}
		
		$html = '
		<table class="table" border="1" align="center">
		<thead>
			<tr>	
				<th class="text-center bg-dark text-white" scope="col" colspan="9">
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-warning" href="../index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto"><h3>Lista de Vehículos</h3></span>
							</div>
				</th>
				
			</tr>
			<tr>
				'.$BotonesADM.'
			</tr>
			<tr class="text-center bg-dark text-white">
				<th class="text-center bg-dark text-white scope="col">Placa</th>
				<th class="text-center bg-dark text-white scope="col" >Marca</th>
				<th class="text-center bg-dark text-white scope="col" >Color</th>
				<th class="text-center bg-dark text-white scope="col" >Año</th>
				<th class="text-center bg-dark text-white scope="col" >Avalúo</th>
				<th  class="text-center bg-dark text-white scope="col" colspan="4">Acciones</th>
			</tr>
			<thead>';
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  FROM vehiculo v, color c, marca m WHERE v.marca=m.id AND v.color=c.id;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>

		$placa_buscar = $_SESSION['listaNote']->username; // Reemplaza con la placa que deseas buscar
		/*echo '<br>';
		echo $placa_buscar;*/
		
	
		if ($_SESSION['listaNote']->roles_id == 9) {	
			while ($row = $res->fetch_assoc()) {// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
				/*echo '<br>';
				echo $row['placa'];*/
				if ($row['placa'] == $placa_buscar) { // Filtrar por la placa específica
			
					$d_del = "del/" . $row['id'];
					$d_del_final = base64_encode($d_del);
					$d_act = "act/" . $row['id'];
					$d_act_final = base64_encode($d_act);
					$d_det = "det/" . $row['id'];
					$d_det_final = base64_encode($d_det);    
					$d_det1 = "mat/" . $row['placa'];
					$d_det_final1 = base64_encode($d_det1); 
					$d_det2 = "multa/" . $row['placa'];
					$d_det_final2 = base64_encode($d_det2); 
      
		
					// Reiniciar $Botones para evitar acumulaciones
					$Botones = "";
		
					if ($_SESSION['listaNote']->roles_id == 1) { 
						$Botones = '<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final1 . '">Consulta</a></td>';
						$Botones = '<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final2 . '">Consulta</a></td>';
						
					}    
		
					$html .= '
						<tr class="text-center">
							<td>' . $row['placa'] . '</td>
							<td>' . $row['marca'] . '</td>
							<td>' . $row['color'] . '</td>
							<td>' . $row['anio'] . '</td>
							<td>' . $row['avaluo'] . '</td>
						
							<td><a class="btn btn-outline-success btn-lg " href="index.php?d=' . $d_det_final1 . '">Consulta</a></td>
							<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>
       							<td><a class="btn btn-outline-secondary btn-lg" href="../Vehiculo/index.php?d=' . base64_encode('getmulta/' . $_SESSION['listaNote']->username) . '">Consultar Multa</a></td>
							' . $Botones . '
						</tr>';
				}
			}
			$html .= '
			<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="../index.php">Regresar</a></th>
			</table>';
		}else{
			while($row = $res->fetch_assoc()  ){// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);    
				$d_det1 = "mat/" . $row['placa'];
				$d_det_final1 = base64_encode($d_det1);        
				$d_det2 = "addmat/" . $row['placa'];
				$d_det_final2 = base64_encode($d_det2);   
				$d_det3 = "addmulta/" . $row['placa'];
				$d_det_final3 = base64_encode($d_det3);     
				// Reiniciar $Botones para evitar acumulaciones
				$Botones = "";
			
				if ($_SESSION['listaNote']->roles_id == 6) { 
					$Botones .= '<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>';
					$Botones .= '<td><a class="btn btn-outline-success btn-lg " href="index.php?d=' . $d_det_final1 . '">Consulta</a></td>';
					$Botones .= '<td><a class="btn btn-outline-warning btn-lg " href="index.php?d=' . $d_det_final2 . '">Matricular</a></td>';
					$Botones .= '<td><a class="btn btn-outline-danger btn-lg " href="index.php?d=' . $d_det_final3 . '">Multar</a></td>';
				}  
				if ($_SESSION['listaNote']->roles_id == 7) { 
					$Botones .= '<td><a class="btn btn-outline-danger custom-btn-1 btn-lg " href="index.php?d=' . $d_del_final . '">Borrar</a></td>';
					$Botones.='<td><a class="btn btn-outline-success btn-lg "  href="index.php?d=' . $d_act_final . '">Actualizar</a></td>';
					$Botones .= '<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>';
					
				}   

			
				$html .= '
					<tr class="text-center">
						<td>' .$row['placa'] . '</td>
						<td>' . $row['marca'] . '</td>
						<td>' . $row['color'] . '</td>
						<td>' . $row['anio'] . '</td>
						<td>' . $row['avaluo'] . '</td>
						
						
		
						'.$Botones.'
					</tr>';
			}
			$html .= '

			<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="../index.php">Regresar</a></th>
			
				
			</table>';

		}

		
		
		return $html;
		
	}
	
	
	public function get_detail_vehiculo($id){
		$sql = "SELECT v.placa, m.descripcion as marca, v.motor, v.chasis, v.combustible, v.anio, c.descripcion as color, v.foto, v.avaluo  
				FROM vehiculo v, color c, marca m 
				WHERE v.id=$id AND v.marca=m.id AND v.color=c.id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el vehiculo con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el vehiculo con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<table class="table col-lg-2" border="1" align="center">
					<tr>
						<th class="text-center bg-dark text-white" scope="col" >
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-warning" href="index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto"><h4>DATOS DEL VEHÍCULO</h4></span>
							</div>
					</th>
						
					</tr>
					<tr>
						
						<td class="text-center">
						<label for="staticEmail" class="col-sm-2 col-form-label" style=" font-weight: bold;" >Placa :</label>
						<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['placa'] .'</label>
							
						</td>
					</tr>
					<tr>
						<td class="text-center">
						<label for="staticEmail" class="col-sm-2 col-form-label"  style=" font-weight: bold;" >Marca :</label>
						<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['marca'] .'</label>
								
						</td>
						
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;">Motor :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['motor'] .'</label>
							
							</td>
						
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;" >Chasis :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['chasis'] .'</label>
							
							</td>
					
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;" >Combustible :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['combustible'] .'</label>
							
						</td>
						
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;">Año :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['anio'] .'</label>
							
						</td>
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;" >Color :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['color'] .'</label>
							
						</td>
					</tr>
					<tr>
						
						
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;">Avalúo :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['avaluo'] .'</label>
							
						</td>
					</tr>
					<tr>
						<td class="text-center">
							<label for="staticEmail" class=" col-sm-2  col-form-label"  style=" font-weight: bold;">Valor Matrícula: :</label>
							 <label for="staticEmail" class="col-sm-2 col-form-label">'. $this->_calculo_matricula($row['avaluo']) .' USD $</label>
							
						</td>
				
					</tr>			
					<tr >
						<th class="text-center"><img src=../../../imagenes/autos/' . $row['foto'] . ' width="300px"/></th>
					</tr>	
					<tr>
						<th class="text-center" ><a class="btn btn-outline-success"  href="index.php">Regresar</a></th>
					</tr>																						
				</table>';
				
				return $html;
		}
	}
	



	
	public function get_matricula($id){
		/*
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
	*/

	
		$num = 1; // Cambia a 0 si no quieres mostrar la tabla
		if($num == 0){
			$mensaje = "tratar de editar el vehiculo con id= ".$id;
			echo $this->_message_error($mensaje);
		} else { 
			// Comienza la tabla
			$html = '
			<table class="table col-lg-2" border="1" align="center">
				<tr>	
						<th class="text-center bg-dark text-white" scope="col" colspan="5">
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-warning" href="index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto">MATRICULAS REGISTRADAS</span>
							</div>
						</th>
					
				</tr>
				<tr>
					<th class="text-center">Placa</th>
					<th class="text-center">Fecha</th>
					<th class="text-center">Agencia</th>
					<th class="text-center">Año</th>
				</tr>';
	
			// Variable que almacenará si se encontró la placa
			$found = false;
	
			// Recorremos el array de Matriculados
			foreach ($_SESSION['Matriculados'] as $matriculaVehiculo) {
				// Verificamos si la placa coincide
				if ($matriculaVehiculo->placa == $id) {
					// Si coincide, mostramos las matrículas para esa placa
					foreach ($matriculaVehiculo->datos as $data) {
						$html .= '
						<tr>
							<td class="text-center">' . $matriculaVehiculo->placa . '</td>
							
							<td class="text-center">' . $data['fecha'] . '</td>
							<td class="text-center">' . $data['agencia'] . '</td>
							<td class="text-center">' . $data['anio'] . '</td>
						</tr>';
					}
					$found = true; // Marcamos que encontramos la placa
					break; // Terminamos el ciclo si encontramos la placa
				}
			}
	
			// Si no se encontró la placa, mostramos un mensaje
			if (!$found) {
				$html .= '
				<tr>
					<td class="text-center" colspan="4">No se encontraron registros para la placa: ' . $id . '</td>
				</tr>';
			}
	
			// Cierre de la tabla
			$html .= '
				
			</table>';
	
			return $html;
		}
	}


	public function matricular($id){
		$num = 1; // Cambia a 0 si no quieres mostrar la tabla
		if($num == 0){
			$mensaje = "tratar de editar el vehiculo con id= ".$id;
			echo $this->_message_error($mensaje);
		} else { 
			$html = '<table class="table col-lg-2" border="1" align="center">
				<tr>
					<th class="text-center bg-dark text-white" scope="col" colspan="5">
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-warning" href="index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto">MATRICULAS REGISTRADAS</span>
							</div>
						</th>
				</tr>
				<tr>
					<th class="text-center">Placa</th>
					<th class="text-center">Fecha</th>
					<th class="text-center">Agencia</th>
					<th class="text-center">Año</th>
				</tr>';
	
			$found = false;
			$aniosMatriculados = [];
	
			foreach ($_SESSION['Matriculados'] as $matriculaVehiculo) {
				if ($matriculaVehiculo->placa == $id) {
					foreach ($matriculaVehiculo->datos as $data) {
						$html .= '<tr>
							<td class="text-center">' . $matriculaVehiculo->placa . '</td>
						
							<td class="text-center">' . $data['fecha'] . '</td>
							<td class="text-center">' . $data['agencia'] . '</td>
							<td class="text-center">' . $data['anio'] . '</td>
						</tr>';
						$aniosMatriculados[] = $data['anio'];
					}
					$found = true;
					break;
				}
			}
	
			if (!$found) {

				$sqlidv = "SELECT v.id  
						FROM vehiculo v
						WHERE v.placa='$id';";
				$res = $this->con->query($sqlidv);
				$rowidv = $res->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
				
				$num = $res->num_rows;


				$html .= '<tr>
					<td class="text-center" colspan="4">No se encontraron registros para la placa: ' . $id . '</td>
				</tr>';
		
			}
	  
			  $html .= '
			  </table>';
	  

			  



			  $agencia=null;
		if($found){
			
			  // Obtener los años disponibles (suponiendo que los años van desde 2000 hasta el actual)
			  $anioActual = date('Y');
			  $aniosDisponibles = array_diff(range(2000, $anioActual), $aniosMatriculados);


			  $op = "mat";

		
		$html .= '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS VEHÍCULO</th>
				</tr>
				<tr>
					<td>IdVehiculo:</td>
					<td>
						<input type="text" size="6" value="'. $matriculaVehiculo->idvehiculo.'" disabled>
						<input type="hidden" name="idvehiculo" value="'.$matriculaVehiculo->idvehiculo.'">
					</td>
				</tr>
				<tr>
					<td>Fecha:</td>
					<td><input type="date" size="15" name="fecha" required></td>
				</tr>	
				<tr>
					<td>agencia:</td>
					<td>' .$this->_get_combo_db('agencia','id','descripcion','agencia',$agencia) . '</td>
				</tr>
				<tr>
					<td>año:</td>
					<td><select name="anio" class="form-select form-select-sm" aria-label=".form-select-sm example" required>';
					
					foreach ($aniosDisponibles as $anio) {
						$html .= '<option value="' . $anio . '">' . $anio . '</option>';
					}
			
					$html .= '</select><br>';
			$html .= 	'</td>
				</tr>

			
				<tr>

					<th  class="text-center" colspan="2">
						<button class="btn btn-outline-success" type="submit" name="Guardar"  value="GUARDAR">
							<i class="fas fa-save"></i> GUARDAR
						</button>

						<a class="btn btn-outline-danger" href="index.php">
							<i class="fas fa-times"></i> CANCELAR
						</a>
					</th>
				
				</tr>												
			</table>';
		}else{

			
			  // Obtener los años disponibles (suponiendo que los años van desde 2000 hasta el actual)
			  $anioActual = date('Y');
			  $aniosDisponibles = array_diff(range(1990, $anioActual));


			  $op = "mat";

		

			$html .= '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					<th class="text-center bg-dark text-white scope="col" colspan="2">DATOS VEHÍCULO</th>
				</tr>

				
				<tr>
					<td>IdVehiculo:</td>
					<td>
						<input type="text" size="6" value="'. $rowidv['id'].'" disabled>
						<input type="hidden" name="idvehiculo" value="'.$rowidv['id'] .'">
					</td>
				</tr>
				<tr>
					<td>Fecha:</td>
					<td><input type="date" size="15" name="fecha" required></td>
				</tr>	
				<tr>
					<td>agencia:</td>
					<td>' .$this->_get_combo_db('agencia','id','descripcion','agencia',$agencia) . '</td>
				</tr>
				<tr>
					<td>año:</td>
					<td><select name="anio" class="form-select form-select-sm" aria-label=".form-select-sm example" required>';
					
							foreach ($aniosDisponibles as $anio) {
								$html .= '<option value="' . $anio . '">' . $anio . '</option>';
							}
					
							$html .= '</select><br>';
					$html .= 	'</td>
				</tr>

			
				<tr>
					<th  class="text-center" colspan="2">
						<button class="btn btn-outline-success" type="submit" name="Guardar"  value="GUARDAR">
							<i class="fas fa-save"></i> GUARDAR
						</button>

						<a class="btn btn-outline-danger" href="index.php">
							<i class="fas fa-times"></i> CANCELAR
						</a>
					</th>
				</tr>												
			</table>';
		}
	
			return $html;
		}
	}

//************************************* MULTAR ****************************************************
	public function multar($id){

		$conexion = new mysqli("localhost", "root", "", "matriculacionfinal");
		if ($conexion->connect_errno) {
		die("Error de conexión: " . $conexion->connect_error);
		}

		$colorPuntos=null;
		$html = '';
		$num = 1; // Cambia a 0 si no quieres mostrar la tabla
		if($num == 0){
			$mensaje = "tratar de editar el vehiculo con id= ".$id;
			echo $this->_message_error($mensaje);
		} else { 

			$sql = "SELECT 
					v.id AS ID_VEHICULO,
					v.placa,
					p.ID_CHOFER,
					p.APELLIDO,
					p.NOMBRE,
					p.CEDULA,
					p.PUNTOS_LICENCIA
					FROM 
						matriculacionfinal.vehiculo v
					JOIN 
						matriculacionfinal.persona p on p.ID_CHOFER=v.id_persona
					
					where v.placa='$id';";



			if ($stmt1 = $conexion->prepare($sql)) {
				$stmt1->execute();
				$result = $stmt1->get_result();
				$row = $result->fetch_assoc();
				
				
			if($result->num_rows != 0) {
				$Propietario_idpersona = $row['ID_CHOFER'];
				$Propietario_apellido = $row['APELLIDO'];
				$Propietario_nombre = $row['NOMBRE'];
				$Propietario_cedula = $row['CEDULA'];
				$Propietario_puntosLicencia = $row['PUNTOS_LICENCIA'];
				$Propietario_placa = $row['placa'];

				if($Propietario_puntosLicencia >= 15){
					$colorPuntos = "table-success";
				}
				if($Propietario_puntosLicencia <= 14 && $Propietario_puntosLicencia >= 10){
					$colorPuntos = "table-warning";
				}
				if($Propietario_puntosLicencia <= 9 && $Propietario_puntosLicencia >= 5){
					$colorPuntos = "table-danger";
				}
				if($Propietario_puntosLicencia <= 5){
					$colorPuntos = "table-danger";
				}
				
				$html.='
				<table class="table col-lg-2" border="1" align="center">
					<tr>
						<th class="text-center bg-primary text-white" scope="col" colspan="6">
									<div class="d-flex justify-content-between align-items-center">
										<a class="btn btn-outline-light" href="index.php">
											<i class="fas fa-arrow-left"></i> 
										</a>
										<span class="mx-auto">DATOS DEL PROPIETARIO</span>
									</div>
								</th>

					</tr>
					<tr>
						
						<th class="text-center"></th>
						<th class="text-center">Cedula</th>
						<th class="text-center">Apellido</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Placa</th>
						<th class="text-center '.$colorPuntos.'">Puntos </th>
						
					</tr>
					<tr>
						
						<td class="text-center"></td>
					
						<td class="text-center"> ' . $Propietario_cedula. '</td>
						<td class="text-center">' . $Propietario_apellido . '</td>
						<td class="text-center">' . $Propietario_nombre . '</td>
						<td class="text-center">' . $Propietario_placa . '</td>
						<td class="text-center '.$colorPuntos.'">' . $Propietario_puntosLicencia . '</td>
					</tr>
				';
				
				$multar=true;

			}else{


				$html.= '
					<table class="table col-lg-2" border="1" align="center">
					<tr>
					<th class="text-center bg-primary text-white" scope="col" colspan="6">
									<div class="d-flex justify-content-between align-items-center">
										<a class="btn btn-outline-light" href="index.php">
											<i class="fas fa-arrow-left"></i> 
										</a>
										<span class="mx-auto">DATOS DEL PROPIETARIO</span>
									</div>
								</th>
					</tr>
					<tr>
					<th class="text-center bg-danger text-white" colspan="6">No tiene asignado algun propietario</th>
					</tr>
					';
				$multar=false;
				
			}
			$stmt1->close();
			}
			
			$found = false;
	
			foreach ($_SESSION['Multados'] as $multaVehiculo) {
				if ($multaVehiculo->placa == $id ) {

					
					$html.= '
							<tr>
								<th class="text-center bg-dark text-white" colspan="6">DATOS DEL MULTAS DEL VEHICULO</th>
							</tr>
							<tr>
								<th class="text-center">Placa</th>
								<th class="text-center">ID_Vehiculo</th>
								<th class="text-center">Fecha</th>
								<th class="text-center">Categoria</th>
								<th class="text-center">Descripcion</th>
								<th class="text-center">Puntos a Restar</th>
							</tr>';
			
							foreach ($multaVehiculo->datos as $data) {
								$html .= '<tr>
									<td class="text-center">' . $multaVehiculo->placa . '</td>
									<td class="text-center">' . $multaVehiculo->idvehiculo . '</td>
									<td class="text-center">' . $data['Mfecha'] . '</td>
									<td class="text-center">' . $data['Mcategoria'] . '</td>
									<td class="text-center">' . $data['Mdescripcion'] . '</td>
									<td class="text-center">' . $data['Mpuntos'] . '</td>
			

								</tr>';
								
							}
						$found = true;
						break;
				}
			}
	
			if (!$found) {

				$sqlidv = "SELECT v.id  
						FROM vehiculo v
						WHERE v.placa='$id';";
				$res = $this->con->query($sqlidv);
				$rowidv = $res->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
				
				$num = $res->num_rows;


				$html .= '
				<table class="table col-lg-2" border="1" align="center">
				<tr>
					<th class="text-center bg-dark text-white" colspan="6">DATOS DEL MULTAS DEL VEHICULO</th>
				</tr>
				<tr>
					<td class="text-center table-success" colspan="4">No se encontraron registros para la placa: ' . $id . '</td>
				</tr>';
		
			}
	  
			  $html .= '

			  
			  </table>';
	  

			  



			  $agencia=null;
			  $multa=null;
		if($found && $multar){
			
			  // Obtener los años disponibles (suponiendo que los años van desde 2000 hasta el actual)

			  $op = "multa";
					$html .= '

				<div class="container">
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<form name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
							
							<input type="hidden" name="id" value="' . $id  . '">
							<input type="hidden" name="op" value="' . $op  . '">
								
								<table class="table " border="1" align="center">
										<tr>
											<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS MULTA</th>
										</tr>
										<tr>
											<td>IdVehiculo:</td>
											<td>
												<input type="text" size="6" value="'. $multaVehiculo->idvehiculo.'" disabled>
												<input type="hidden" name="Midvehiculo" value="'.$multaVehiculo->idvehiculo.'">
											</td>
											
										</tr>
										<tr>
											<td size="2">Multa realizada por el Usuario Con ID: </td>
											<td>
											<input type="text" size="2" value="'. $_SESSION['listaNote']->id.'" disabled>
											<input type="hidden"  size="2" name="Midusuario" value="' .$_SESSION['listaNote']->id . '" required>
											</td>
										</tr>
										<tr>
											<td>Cedula:</td>
											<td>
											<input type="text" size="10" value="'. $multaVehiculo->cedula.'" disabled>
											<input type="hidden"  size="10" name="Mcedulapersona" value="' .$multaVehiculo->cedula . '">
											</td>
										</tr>
										
										<tr>
											<td>Multa:</td>
											<td>' .$this->_get_combo_db('tipo_multas','ID_TIPO','DESCRIPCION','Mmulta',$multa) . '</td>
										</tr>

										<tr>
											<td>Fecha:</td>
											<td><input type="date" size="15" name="Mfecha" required></td>
										</tr>

									
										<tr>
										<th  class="text-center" colspan="2">
											<button class="btn btn-outline-success" type="submit" name="Guardar"  value="GUARDAR">
												<i class="fas fa-save"></i> GUARDAR
											</button>

											<a class="btn btn-outline-danger" href="index.php">
												<i class="fas fa-times"></i> CANCELAR
											</a>
										</th>												
									</table>
							</form>

						</div>



						<div class="col-lg-6 col-md-6">
				
							<table class="table table-bordered">
								<thead class="thead-dark">
									<tr>
										<th class="text-center bg-info text-white" colspan="6">Tabla de Multas</th>
									</tr>
									<tr>
										<th>Descripción</th>
										<th>Gravedad</th>
										<th>Puntos a restar</th>
									</tr>
								</thead>
								<tbody>';

						$sqlMul = "SELECT * FROM matriculacionfinal.tipo_multas;";

						//echo $sqlMul;
				
						$result = $this->con->query($sqlMul);
			
						$num = $result->num_rows;
										 
						

                    if ($result->num_rows != 0) {
						// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
                        while ($rowidv = $result->fetch_assoc()) {
							$html .= '<tr >
                                    <td>'.$rowidv['DESCRIPCION'].'</td>
									<td>'.$rowidv['CATEGORIA'].'</td>
									<td class="text-center">'.$rowidv['PUNTOS'].'</td>
                                  </tr>';
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No hay multas registradas</td></tr>";
                    }
		
				$html .= '	
								</tbody>
							</table>
						</div>

						
					</div>
				</div>';
		} elseif($multar){

			  $op = "multa";		
	
			  $html .= '

			  <div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<form  name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
				
							<input type="hidden" name="id" value="' . $id  . '">
							<input type="hidden" name="op" value="' . $op  . '">
							
							 	<table class="table " border="1" align="center">
									<tr>
										<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS MULTA</th>
									</tr>
									<tr>
										<td>IdVehiculo:</td>
											<td>
												<input type="text" size="6" value="'. $rowidv['id'].'" disabled>
												<input type="hidden" name="Midvehiculo" value="'.$rowidv['id'].'">
											</td>
									</tr>
									<tr>
										<td size="2">Multa realizada por el Usuario Con ID: </td>
											<td>
												<input type="text" size="2" value="'. $_SESSION['listaNote']->id.'" disabled>
												<input type="hidden"  size="2" name="Midusuario" value="' .$_SESSION['listaNote']->id . '" required>
											</td>
									</tr>
									
									<tr>
											<td>Cedula:</td>

											<td>
											<input type="text" size="10" value="'. $Propietario_cedula.'" disabled>
											<input type="hidden"  size="10" name="Mcedulapersona" value="' .$Propietario_cedula . '">
											</td>
									</tr>
									<tr>
										<td>Multa:</td>
										<td>' .$this->_get_combo_db('tipo_multas','ID_TIPO','DESCRIPCION','Mmulta',$multa) . '</td>
									</tr>

									<tr>
										<td>Fecha:</td>
										<td><input type="date" size="15" name="Mfecha" required></td>
									</tr>

								
									<tr>
											<th  class="text-center" colspan="2">
											<button class="btn btn-outline-success" type="submit" name="Guardar"  value="GUARDAR">
												<i class="fas fa-save"></i> GUARDAR
											</button>

											<a class="btn btn-outline-danger" href="index.php">
												<i class="fas fa-times"></i> CANCELAR
											</a>
										</th>	
									</tr>												
								</table>
						
						</form>

			  		</div>

					<div class="col-lg-6 col-md-6">
						<table class="table table-bordered">
									<thead class="thead-dark">
										<tr>
											<th class="text-center bg-info text-white" colspan="6">Tabla de Multas</th>
										</tr>
										<tr>
											<th>Descripción</th>
											<th>Gravedad</th>
											<th>Puntos a restar</th>
										</tr>
									</thead>
									<tbody>';

										$sqlMul = "SELECT * FROM matriculacionfinal.tipo_multas;";

										//echo $sqlMul;
								
										$result = $this->con->query($sqlMul);
							
										$num = $result->num_rows;
														
										echo	$num;

											if ($result->num_rows != 0) {
												// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
												while ($rowidv = $result->fetch_assoc()) {
													$html .= '<tr >
															<td>'.$rowidv['DESCRIPCION'].'</td>
															<td>'.$rowidv['CATEGORIA'].'</td>
															<td class="text-center">'.$rowidv['PUNTOS'].'</td>
														</tr>';
												}
											} else {
												echo "<tr><td colspan='3' class='text-center'>No hay multas registradas</td></tr>";
											}
								
										$html .= '	
									</tbody>
							</table>
						</div>
						
			  		</div>
			  	</div>
			  </div>
			  ';
				
		}else{
			$html .= '
				<div class="container mt-5">
				<div class="row justify-content-center">
					<div class="col-md-6">
						<div class="card shadow-sm border-danger">
							<div class="card-header bg-danger text-white">
								<h4>ATENCION</h4>
							</div>
							<div class="card-body">
								<p class="text-muted">Para poder registrar una multa el vehiculo debe tener un propietario</p>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			';
		}
	
			return $html;
		}
	}



	
	public function delete_vehiculo($id){
		$sql = "DELETE FROM vehiculo WHERE id=$id;";
			if($this->con->query($sql)){
			echo $this->_message_ok("ELIMINÓ");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	
//*************************************************************************

	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//*************************************************************************	
	
	private function _message_error($tipo){
		$html= '
		<table class="table border border-2 rounded-3 mx-auto text-center mt-5">
	   <tr>
		   <th class=" bg-dark text-white py-5" scope="col">El registro se ' . $tipo . ' correctamente</th>
	   </tr>
	   <tr>
		   <th class="py-2"><a class="btn btn-outline-warning " href="index.php">Regresar</a></th>
	   </tr>
   </table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html= '
		  <table class="table border border-2 rounded-3 mx-auto text-center mt-5">
        <tr>
            <th class=" bg-dark text-white py-5" scope="col">El registro se ' . $tipo . ' correctamente</th>
        </tr>
        <tr>
            <th class="py-2"><a class="btn btn-outline-warning " href="index.php">Regresar</a></th>
        </tr>
    </table>';
		return $html;
	}
	
//****************************************************************************	

//************************************* GET_MULTAR ****************************************************
	public function get_multar($id){

		$conexion = new mysqli("localhost", "root", "", "matriculacionfinal");
		if ($conexion->connect_errno) {
			die("Error de conexión: " . $conexion->connect_error);
		}
	
		$colorPuntos=null;
		$html = '';
		$num = 1; // Cambia a 0 si no quieres mostrar la tabla
		if($num == 0){
			$mensaje = "tratar de editar el vehiculo con id= ".$id;
			echo $this->_message_error($mensaje);
		} else { 
	
			$sql = "SELECT 
					v.id AS ID_VEHICULO,
					v.placa,
					p.ID_CHOFER,
					p.APELLIDO,
					p.NOMBRE,
					p.CEDULA,
					p.PUNTOS_LICENCIA
					FROM 
						matriculacionfinal.vehiculo v
					JOIN 
						matriculacionfinal.persona p on p.ID_CHOFER=v.id_persona
					
					where v.placa='$id';";
	
			if ($stmt1 = $conexion->prepare($sql)) {
				$stmt1->execute();
				$result = $stmt1->get_result();
				$row = $result->fetch_assoc();
				
				if($result->num_rows != 0) {
					$Propietario_idpersona = $row['ID_CHOFER'];
					$Propietario_apellido = $row['APELLIDO'];
					$Propietario_nombre = $row['NOMBRE'];
					$Propietario_cedula = $row['CEDULA'];
					$Propietario_puntosLicencia = $row['PUNTOS_LICENCIA'];
					$Propietario_placa = $row['placa'];
	
					if($Propietario_puntosLicencia >= 15){
						$colorPuntos = "table-success";
					}
					if($Propietario_puntosLicencia <= 14 && $Propietario_puntosLicencia >= 10){
						$colorPuntos = "table-warning";
					}
					if($Propietario_puntosLicencia <= 9 && $Propietario_puntosLicencia >= 5){
						$colorPuntos = "table-danger";
					}
					if($Propietario_puntosLicencia <= 5){
						$colorPuntos = "table-danger";
						$html .= '
						<!-- Modal de Advertencia -->
						<div class="modal fade" id="modalAdvertencia" tabindex="-1" aria-labelledby="modalAdvertenciaLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header bg-danger text-white">
							<h5 class="modal-title" id="modalAdvertenciaLabel">¡Advertencia!</h5>
							<!-- Corregir la "X" para cerrar -->
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
						</div>
						<div class="modal-body">
							El propietario de este vehículo tiene 5 puntos o menos en su licencia. ¡Tome precauciones!
						</div>
						<div class="modal-footer">
							<!-- Corregir el botón para cerrar -->
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Entendido</button>
						</div>
						</div>
					</div>
						</div>';
					// Código JavaScript para abrir el modal automáticamente
					$html .= "<script>$(document).ready(function() { $('#modalAdvertencia').modal('show'); });</script>";
					}
	




					
	
					$html.='
					<table class="table col-lg-2" border="1" align="center">
						<tr>
						<th class="text-center bg-primary text-white" scope="col" colspan="6">
							<div class="d-flex justify-content-between align-items-center">
								<a class="btn btn-outline-light" href="index.php">
									<i class="fas fa-arrow-left"></i> 
								</a>
								<span class="mx-auto">DATOS DEL PROPIETARIO</span>
							</div>
						</th>
						
						</tr>
						<tr>
							
							<th class="text-center"></th>
							<th class="text-center">Cedula</th>
							<th class="text-center">Apellido</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Placa</th>
							<th class="text-center '.$colorPuntos.'">Puntos </th>
							
						</tr>
						<tr>
							
							<td class="text-center"></td>
						
							<td class="text-center"> ' . $Propietario_cedula. '</td>
							<td class="text-center">' . $Propietario_apellido . '</td>
							<td class="text-center">' . $Propietario_nombre . '</td>
							<td class="text-center">' . $Propietario_placa . '</td>
							<td class="text-center '.$colorPuntos.'">' . $Propietario_puntosLicencia . '</td>
						</tr>
					';
					
					$multar=true;
	
				}else{
	
					$html.= '
						<table class="table col-lg-2" border="1" align="center">
						<tr>
						<th class="text-center bg-primary text-white" colspan="6">DATOS DEL PROPIETARIO</th>
						</tr>
						<tr>
						<th class="text-center bg-danger text-white" colspan="6">No tiene asignado algun propietario</th>
						</tr>
						';
					$multar=false;
					
				}
				$stmt1->close();
			}
			
			$found = false;
	
			foreach ($_SESSION['Multados'] as $multaVehiculo) {
				if ($multaVehiculo->placa == $id ) {
	
					
					$html.= '
							<tr>
								<th class="text-center bg-primary text-white" scope="col" colspan="6">
									<div class="d-flex justify-content-between align-items-center">
										<a class="btn btn-outline-light" href="index.php">
											<i class="fas fa-arrow-left"></i> 
										</a>
										<span class="mx-auto">DATOS DEL PROPIETARIO</span>
									</div>
								</th>
							</tr>
							<tr>
								<th class="text-center">Placa</th>
								<th class="text-center">ID_Vehiculo</th>
								<th class="text-center">Fecha</th>
								<th class="text-center">Categoria</th>
								<th class="text-center">Descripcion</th>
								<th class="text-center">Puntos a Restar</th>
							</tr>';
		
							foreach ($multaVehiculo->datos as $data) {
								$html .= '<tr>
									<td class="text-center">' . $multaVehiculo->placa . '</td>
									<td class="text-center">' . $multaVehiculo->idvehiculo . '</td>
									<td class="text-center">' . $data['Mfecha'] . '</td>
									<td class="text-center">' . $data['Mcategoria'] . '</td>
									<td class="text-center">' . $data['Mdescripcion'] . '</td>
									<td class="text-center">' . $data['Mpuntos'] . '</td>
		
	
								</tr>';
								
							}
						$found = true;
						break;
				}
			}
	
			if (!$found) {
	
				$sqlidv = "SELECT v.id  
						FROM vehiculo v
						WHERE v.placa='$id';";
				$res = $this->con->query($sqlidv);
				$rowidv = $res->fetch_assoc();// fetch_assoc() es un método de MySQLi en PHP que se usa para obtener la siguiente filade un conjunto de resultados como un array asociativo
				
				$num = $res->num_rows;
	
				$html .= '
				<table class="table col-lg-2" border="1" align="center">
				<tr>
					<th class="text-center bg-dark text-white" colspan="6">DATOS DE LAS MULTAS DEL VEHICULO</th>
				</tr>
				<tr>
					<td class="text-center table-success" colspan="4">No se encontraron registros para la placa: ' . $id . '</td>
				</tr>';
		
			}
	  
			  $html .= '
	
			  <tr>
				  <th class="text-center" colspan="6"><a class="btn btn-outline-success" href="..\index.php">Regresar</a></th>
			  </tr>
			  </table>';
	  
			  return $html;
		}
	}

//************************************* GET_MULTAR2 ****************************************************

	public function get_multar2($id) {
		$conexion = new mysqli("localhost", "root", "", "matriculacionfinal");
		if ($conexion->connect_errno) {
			die("Error de conexión: " . $conexion->connect_error);
		}
	
		$html = '';
	
		$sql = "SELECT 
				v.id AS ID_VEHICULO,
				v.placa,
				p.ID_CHOFER,
				p.APELLIDO,
				p.NOMBRE,
				p.CEDULA,
				p.PUNTOS_LICENCIA
				FROM 
					matriculacionfinal.vehiculo v
				JOIN 
					matriculacionfinal.persona p on p.ID_CHOFER=v.id_persona
				WHERE v.placa='$id';";
	
		if ($stmt1 = $conexion->prepare($sql)) {
			$stmt1->execute();
			$result = $stmt1->get_result();
			$row = $result->fetch_assoc();
	
			if ($result->num_rows != 0) {
				$Propietario_puntosLicencia = $row['PUNTOS_LICENCIA'];
	
				if ($Propietario_puntosLicencia <= 5) {
					$html .= '
					<!-- Modal de Advertencia -->
					<div class="modal fade" id="modalAdvertencia" tabindex="-1" aria-labelledby="modalAdvertenciaLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header bg-danger text-white">
									<h5 class="modal-title" id="modalAdvertenciaLabel">¡Advertencia!</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
								</div>
								<div class="modal-body">
									El propietario de este vehículo tiene 5 puntos o menos en su licencia. ¡Tome precauciones!
									<br>
									Para más información en nuestro sitio web en: Matricula - Consultar Multa.
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Entendido</button>
								</div>
							</div>
						</div>
					</div>';
					// Código JavaScript para abrir el modal automáticamente
					$html .= "<script>$(document).ready(function() { $('#modalAdvertencia').modal('show'); });</script>";
				}
			}
			$stmt1->close();
		}
	
		return $html;
	}
	
} // FIN SCRPIT
?>

