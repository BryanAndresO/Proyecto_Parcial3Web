<?php
class usuario1{
	private $id;
	private $username;
	private $password;
	private $roles_id;
	private $con;

	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_vehiculo() **************************************************	
	
	public function update_vehiculo(){

		$this->id = $_POST['id'];
		$this->username= $_POST['username'];	
		$this->password = $_POST['password'];
		$this->roles_id = $_POST['marcaCMB'];
		
		
		
		$sql = "UPDATE usuarios SET
									username ='$this->username',
									password ='$this->password',
									roles_id ='$this->roles_id'
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
    // Escapar los valores para prevenir inyección SQL
    $this->username = $this->con->real_escape_string($_POST['username']);
    $this->password = $this->con->real_escape_string($_POST['password']);
    $this->roles_id = intval($_POST['marcaCMB']);  // Asegurar que roles_id es un número

	if($this->username !=null){
		  // Verificar si el username ya existe en la tabla `usuarios`
		  $sql_check_user = "SELECT id FROM usuarios WHERE username = '$this->username'";


		  // Ejecutar la consulta
		  $result_user = $this->con->query($sql_check_user);
	  
		  // Verificar si hubo error en la consulta
		  if ($result_user === false) {
			  echo "Error en la consulta: " . $this->con->error;
			  return;
		  }
	  
		  // Verificar si el username ya existe
		  if ($result_user->num_rows > 0) {
			  
			  echo $this->_message_error("El username ya está REGISTRADO ingrese otro username.");
			  return;
		  }
		      // Si pasa la validación, insertar el registro
			  $sql = "INSERT INTO usuarios (username, password, roles_id) VALUES ('$this->username', '$this->password', '$this->roles_id')";

			  // Depuración: Imprimir la consulta de inserción
			  echo "Consulta para insertar el usuario: " . $sql . "<br>";
			  if ($this->con->query($sql)) {
				echo $this->_message_ok("guardó");
			} else {
				echo $this->_message_error("guardar");
			}
	}
  



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
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	/*private function _get_combo_dbVe($baseDatos, $tabla, $valor, $etiqueta, $nombre, $defecto) {
		$html = '<select class="form-control" name="' . $nombre . '">';
		$sql = "SELECT $valor, $etiqueta FROM $baseDatos.$tabla;"; // Se incluye el nombre de la BD
		$res = $this->con->query($sql);
		
		while ($row = $res->fetch_assoc()) {
			$html .= ($defecto == $row[$valor]) ? 
				'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : 
				'<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		
		$html .= '</select>';
		return $html;
	}
	*/

	private function _get_combo_dbVe($baseDatos, $tabla, $valor, $etiqueta, $nombre, $defecto) {
		$html = '<select class="form-control" name="' . $nombre . '">';
		
		// SQL modificado para filtrar vehículos cuyas placas no estén en la tabla usuarios
		$sql = "
			SELECT placa
			FROM matriculacionfinal.vehiculo
			WHERE placa NOT IN (
				SELECT username COLLATE utf8mb3_general_ci
				FROM matriculacionfinal.usuarios
			);
		";
	
		// Ejecutar la consulta
		$res = $this->con->query($sql);
	
		// Verificar si hay resultados
		if ($res->num_rows > 0) {
			// Si hay resultados, generar las opciones
			while ($row = $res->fetch_assoc()) {
				$html .= ($defecto == $row[$valor]) ? 
					'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : 
					'<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
			}
		} else {
			// Si no hay resultados, mostrar un mensaje
			$html = '
					<table class="table border border-2 rounded-3 mx-auto text-center mt-5">
				<tr>
					<th class=" bg-dark text-white py-5" scope="col">No se encontraron vehículos para crear usuarios </th>
				</tr>
				<tr>
					<th class="py-2"><a class="btn btn-outline-warning " href="index.php">Regresar</a></th>
				</tr>
			</table>';
		
	
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


			$this->username= NULL;	
			$this->password = NULL;
			$this->roles_id = NULL;

			
			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM usuarios WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar el usuario con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
		/*	echo "<br>TUPLA <br>";
				echo "<pre>";
					print_r($row);
				echo "</pre>";
			*/	
				

				$this->username = $row['username'];
				$this->password = $row['password'];
				$this->roles_id = $row['roles_id'];
				
				$flag = "disabled";
				$op = "update";
			}
		}
		
		if($_SESSION['BOTON']==9 && $op=="new"){
			$html = '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS Usuario</th>
				</tr>
				<tr>
					<td>Username:</td>
					<td> '. $this->_get_combo_dbVe('matriculacionfinal','vehiculo','placa','placa','username',$this->username). ' </td>
				</tr>
								<tr>
					<td>password:</td>
					<td><input type="text"  size="6" name="password" value="' . $this->password . '" required></td>
				</tr>
				</tr>
				<tr>
					<td>ROL:</td>

					<td><input type="text"  size="6" name="marcaCMB" value="' . $_SESSION['BOTON'] . '" required></td>
				</tr>
				<tr>
					
					<th  class="text-center" colspan="2"><input  class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>
				<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="index.php">Regresar</a></th>												
			</table>';
		}elseif($_SESSION['BOTON']==6 && $op=="new"){
			$html = '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS Usuario</th>
				</tr>
				<tr>
					<td>Username:</td>
					<td><input type="text"  size="6" name="username" value="' . $this->username . '" required></td>
				</tr>
								<tr>
					<td>password:</td>
					<td><input type="text"  size="6" name="password" value="' . $this->password . '" required></td>
				</tr>
				</tr>
				<tr>
					<td>ROL:</td>

					<td><input type="text"  size="6" name="marcaCMB" value="' . $_SESSION['BOTON'] . '" required></td>
				</tr>
				<tr>
					
					<th  class="text-center" colspan="2"><input  class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>
				<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="index.php">Regresar</a></th>												
			</table>';
		}else{
			$html = '
		<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
  		<table class="table " border="1" align="center">
				<tr>
					<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS Usuario</th>
				</tr>
				<tr>
					<td>Username:</td>
					<td><input type="text"  size="6" name="username" value="' . $this->username. '" required></td>
				</tr>
								<tr>
					<td>password:</td>
					<td><input type="text"  size="6" name="password" value="' . $this->password . '" required></td>
				</tr>
				</tr>
				<tr>
					<td>ROL:</td>

					<td><input type="text"  size="6" name="marcaCMB" value="' . $_SESSION['BOTON'] . '" required></td>
				</tr>
				<tr>
					
					<th  class="text-center" colspan="2"><input  class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
					
				</tr>
				<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="index.php">Regresar</a></th>												
			</table>';
		}

		


		return $html;


	}
	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<table  class="table" border="1" align="center">
		<thead>
			<tr>
				<th  class="text-center bg-dark text-white" colspan="8"><h3>Lista de Usuarios</h3></th>
			</tr>
			<tr>
				<th class="text-center bg-dark " colspan="8"><a class="btn btn-outline-warning px-5 text-white" href="index.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr class="text-center bg-dark text-white">
				<th class="text-center bg-dark text-white scope="col">Username</th>
				<th class="text-center bg-dark text-white scope="col" >Password</th>
				<th class="text-center bg-dark text-white scope="col" >ROL</th>
				<th  class="text-center bg-dark text-white scope="col" colspan="3">Acciones</th>
			</tr>
			<thead>';
		$sql = "SELECT * FROM matriculacionfinal.usuarios;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			if($row['roles_id']==$_SESSION['BOTON']){

				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);					
				$html .= '
				<tr class="text-center">

					<td>' . $row['username'] . '</td>
					<td>' . $row['password'] . '</td>
					<td>' . $row['roles_id'] . '</td>
					<td><a class="btn btn-outline-danger custom-btn-1 btn-lg " href="index.php?d=' . $d_del_final . '">Borrar</a></td>
					<td><a class="btn btn-outline-success btn-lg "  href="index.php?d=' . $d_act_final . '">Actualizar</a></td>
					<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>
				</tr>';
			}
			
		}
		$html .= ' 
		
		<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-success"  href="../index.php">Regresar</a></th>
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_vehiculo($id){
		$sql = "SELECT * FROM matriculacionfinal.usuarios WHERE id=$id ;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		//echo $sql;
        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el vehiculo con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el usuario con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<table class="table col-lg-2" border="1" align="center">
					<tr>
						<th class="text-center bg-dark text-white">DATOS DEL USUARIOS</th>
					</tr>
					<tr>
						
						<td class="text-center">
						<label for="staticEmail" class="col-sm-2 col-form-label" style=" font-weight: bold;" > Username:</label>
						<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['username'] .'</label>
							
						</td>
					</tr>
					<tr>
						<td class="text-center">
						<label for="staticEmail" class="col-sm-2 col-form-label"  style=" font-weight: bold;" >Password :</label>
						<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['password'] .'</label>
								
						</td>
						
					</tr>
					<tr>
						<td class="text-center">
						<label for="staticEmail" class="col-sm-2 col-form-label"  style=" font-weight: bold;" >Rol :</label>
						<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['roles_id'] .'</label>
								
						</td>
						
					</tr>
					<tr>
						<th class="text-center" ><a class="btn btn-outline-success"  href="index.php">Regresar</a></th>
					</tr>																						
				</table>';
				
				return $html;
		}
	}
	
	
	public function delete_vehiculo($id){
		$sql = "DELETE FROM usuarios WHERE id=$id;";
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
		$html = '
		<table class="table border border-2 rounded-3 mx-auto text-center mt-5">
	   <tr>
		   <th class=" bg-dark text-white py-5" scope="col">' . $tipo . ' </th>
	   </tr>
	   <tr>
		   <th class="py-2"><a class="btn btn-outline-warning " href="index.php">Regresar</a></th>
	   </tr>
   </table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
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
	
} // FIN SCRPIT
?>

