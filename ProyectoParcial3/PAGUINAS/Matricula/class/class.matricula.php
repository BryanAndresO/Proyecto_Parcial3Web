<?php




class matricula{
	private $id;
	private $fecha;
	private $idVehiculo;
	private $agencia;
	private $placa;
	private $anio;


	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_vehiculo() **************************************************	
	
	public function update_vehiculo(){
		$this->id = $_POST['id'];
		
		$this->fecha = $_POST['fecha'];
		
			
		

		$this->idVehiculo = $_POST['idvehiculo'];

		$this->agencia = $_POST['colorCMB'];

		$this->anio = $_POST['anio'];
		
		
		
		$sql = "INSERT INTO matricula VALUES(NULL,
											'$this->fecha',
											'$this->idVehiculo',
											'$this->agencia',
											'$this->anio')";
		echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	

//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_vehiculo(){
		
		
		$this->id = $_POST['id'];
		
		$this->fecha = $_POST['motor'];
		$this->idVehiculo = $_POST['idvehiculo'];
			
		$this->agencia = $_POST['colorCMB'];

		$this->anio = $_POST['anio'];
		
		
		 
				echo "<br> FILES <br>";
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";
		     
		
	
		

		
		$sql = "INSERT INTO vehiculo VALUES(NULL,
											'$this->fecha',
											$this->idVehiculo,
											'$this->agencia',
											'$this->anio');";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
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

	private function _get_combo_dbanio($tabla, $valor, $etiqueta, $nombre, $defecto, $idVehiculo) {
		$html = '<select class="form-control" name="' . $nombre . '">';
		
		// Consulta para obtener los años matriculados del vehículo específico
		$sql = "SELECT DISTINCT $etiqueta FROM $tabla WHERE vehiculo = $idVehiculo AND $etiqueta >= 1990";
		$res = $this->con->query($sql);
	
		$matriculados = [];
		while ($row = $res->fetch_assoc()) {
			$matriculados[] = $row[$etiqueta];
		}
	
		// Generamos los años desde 1990 hasta el actual
		$anioActual = date('Y');
		for ($anio = 1990; $anio <= $anioActual; $anio++) {
			if (!in_array($anio, $matriculados)) {
				$selected = ($defecto == $anio) ? 'selected' : '';
				$html .= "<option value='$anio' $selected>$anio</option>\n";
			}
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
		private function _get_combo_anioa($nombre,$anio_inicial,$defecto){
			$html = '<select class="form-control"  name="' . $nombre . '">';
			$anio_actual = 2030;
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
		
		
			$this->fecha = NULL;
			$this->idVehiculo = NULL;
				
			$this->agencia = NULL;
	
			$this->anio = NULL;
			
			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM matricula WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
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
			
				$this->fecha = $row['fecha'];
				$this->placa=$_SESSION['listaNote']->username;
				$this->idVehiculo = $row['vehiculo'];
				$this->agencia = $row['agencia'];
				$this->anio = $row['anio'];

				
				$flag = "disabled";
				$op = "update";
			}
		}
		
		
		$idV = null;
		$html1=null;
		$found = null;
		
		foreach ($_SESSION['Matriculados'] as $matriculaVehiculo) {

			if (isset($matriculaVehiculo->placa) && isset($_SESSION['listaNote']->username) && $matriculaVehiculo->placa == $_SESSION['listaNote']->username) {
				// Si coincide, obtenemos el ID del vehículo asociado
				$idV = $matriculaVehiculo->idvehiculo;
				$found = true; // Marcamos que encontramos la placa
				break; // Terminamos el ciclo si encontramos la placa
			}

	
		

		
		// Si no se encontró la placa, mostramos un mensaje
		if (!$found ) {
			$html1 .= '
			<tr>
				<td class="text-center" colspan="4">No se encontraron registros para la placa: ' . htmlspecialchars($_SESSION['listaNote']->username) . '</td>
			</tr>';
		}

						 

		}

		$validacion=null;

		if (isset($_SESSION['listaNote']) && is_object($_SESSION['listaNote'])) {
			$idVehiculo = $_SESSION['listaNote']->idvehiculo;
			if (isset($idVehiculo)) {
				echo "ID del vehículo: " . $idVehiculo;
			} else {
				
				$html = '
			
					
						<div class="col-md-6 mt-5 mb-5">
							<div class="card shadow-sm border-danger">
								<div class="card-header bg-danger text-white">
									<h4>ERROR EN LA AUTENTICACIÓN</h4>
								</div>
								<div class="card-body">
									<p class="text-muted">El vehiculo que se trata de matricular No esta registrado.</p>
										
						
									<a href="index.php" class="btn btn-primary">Regresar</a>
								</div>
							</div>
						</div>
					
				
				';
				$validacion=1;
			}
		} else {
			echo "No hay un usuario en la sesión.";
		}

		if($idV != 0){
				
			$html = '
			<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
			
			<input type="hidden" name="id" value="' . $id  . '">
			<input type="hidden" name="op" value="' . $op  . '">
			
			  <table class="table " border="1" align="center">
					<tr>
						<th  class="text-center bg-dark text-white scope="col" colspan="2">DATOS VEHÍCULO</th>
					</tr>
					<tr>
						<td>Placa:</td>
						<td><input type="text"  size="15" name="placa" value="' . $this->placa . '" required></td>
					</tr>
						<tr>
							<td>Fecha:</td>
							<td><input type="text" id="fecha" size="15" name="fecha" required></td>
						</tr>
					<tr>
						<td>Vehiculo:</td>
						<td><input type="text" size="15" name="idvehiculo" value="' . $idVehiculo . '" required></td>
					</tr>	
					<tr>
						<td>Agencia:</td>
								<td> '. $this->_get_combo_db("agencia","id","descripcion","colorCMB",$this->agencia). '</td>
					</tr>
					<tr>$
						<td>Año:</td>
						<td>' . $this->_get_combo_dbanio("matricula","id","anio","anio",$this->anio,$idV) . '</td>
						
						
					</tr>
	
	
					<tr>
						<th  class="text-center" colspan="2"><input  class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
					</tr>												
				</table>';
	
			}elseif($validacion!=1){
				$html = '
				<form class="col-lg-5 col-ms-5" name="vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
				
				<input type="hidden" name="id" value="' . $id  . '">
				<input type="hidden" name="op" value="' . $op  . '">
				
				<table class="table " border="1" align="center">
						<tr>
							<th  class="text-center bg-dark text-white scope="col" colspan="2">Matriculacion</th>
						</tr>
						<tr>
							<td>Placa:</td>
							<td><input type="text"  size="15" name="placa" value="' . $this->placa . '" required></td>
						</tr>
						<tr>
							<td>Fecha:</td>
							<td><input type="text" id="fecha" size="15" name="fecha" required></td>
						</tr>
						<tr>
							<td>Vehiculo:</td>
							<td><input type="text" size="15" name="idvehiculo" value="' . $idVehiculo . '" required></td>
						</tr>	

						<tr>
								<td>Agencia:</td>
								<td> '. $this->_get_combo_db("agencia","id","descripcion","colorCMB",$this->agencia). '</td>
					   </tr>
						<tr>
							<td>Año:</td>
							<td>' . $this->_get_combo_anioa("anio",1990,$this->anio) . '</td>
							
							
						</tr>

		
						<tr>
							<th  class="text-center" colspan="2"><input  class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
						</tr>												
					</table>';
		
			}
		return $html;
	}
	
	

	public function get_list(){

	

	

		if (isset($_SESSION['listaNote'])) {
			if ($_SESSION['listaNote'] instanceof Usuario) {
				$roles_id = $_SESSION['listaNote']->roles_id;
				echo "El ID del rol es: " . $roles_id;
			} else {
				echo "Error: El objeto en la sesión no es de tipo Usuario.";
			}
		} else {
			echo "No hay usuario en la sesión.";
		}

		// Definir etiquetas habilitadas por rol
		$menuOpciones = '';
		$Botones = '';

	
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<table class="table" border="1" align="center">
		<thead>
			<tr>
				<th  class="text-center bg-dark text-white" colspan="9"><h3>Matriculas</h3></th>
			</tr>

					
		
			
			<tr class="text-center bg-dark text-white">
				<th class="text-center bg-dark text-white scope="col">Placa</th>
				<th class="text-center bg-dark text-white scope="col" >Fecha</th>
				<th class="text-center bg-dark text-white scope="col" >Agencia</th>
				<th class="text-center bg-dark text-white scope="col" >Vehiculo</th>
				<th class="text-center bg-dark text-white scope="col" >Año</th>
				
				<th  class="text-center bg-dark text-white scope="col" colspan="4">Acciones</th>
			</tr>
			<thead>';
		$sql = "SELECT * FROM matriculacionfinal.matricula;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>

		

		$found = false;
		$placa_buscar = null;
		
		foreach ($_SESSION['Matriculados'] as $matriculaVehiculo) {
			// Verificamos si la placa coincide
			if (isset($matriculaVehiculo->placa) && isset($_SESSION['listaNote']->username) && $matriculaVehiculo->placa == $_SESSION['listaNote']->username) {
				// Si coincide, obtenemos el ID del vehículo asociado
				$placa_buscar = $matriculaVehiculo->idvehiculo;
				$found = true; // Marcamos que encontramos la placa
				break; // Terminamos el ciclo si encontramos la placa
			}
		}
		
		if (!$found) {
			$html .= '
			<tr>
				<td class="text-center" colspan="4">No se encontraron registros para la placa: ' . htmlspecialchars($_SESSION['listaNote']->username) . '</td>
			</tr>

			';
		}
		
		
		
		if ($_SESSION['listaNote']->roles_id == 6) {

			while ($row = $res->fetch_assoc()) {
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);    
				$d_det1 = "mat/" . $row['id'];
				$d_det_final1 = base64_encode($d_det1);  
				
				
				if ($row['vehiculo'] == $placa_buscar) { // Filtrar por la placa específica
			
					$d_del = "del/" . $row['id'];
					$d_del_final = base64_encode($d_del);
					$d_act = "act/" . $row['id'];
					$d_act_final = base64_encode($d_act);
					$d_det = "det/" . $row['id'];
					$d_det_final = base64_encode($d_det);    
					$d_det1 = "mat/" . $row['id'];
					$d_det_final1 = base64_encode($d_det1);  
					
							// Si no se encontró la placa, mostramos un mensaje


		
					// Reiniciar $Botones para evitar acumulaciones
					$Botones = "";
		
					if ($_SESSION['listaNote']->roles_id == 6) { 
						$Botones = '<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final1 . '">Consulta</a></td>';
					}    
		
					$html .= '
						<tr class="text-center">
							<td>' . $_SESSION['listaNote']->username . '</td>
							<td>' . $row['fecha'] . '</td>
							<td>' . $row['vehiculo'] . '</td>
							<td>' . $row['agencia'] . '</td>
							<td>' . $row['anio'] . '</td>
							<!--
							<td><a class="btn btn-outline-danger custom-btn-1 btn-lg " href="index.php?d=' . $d_del_final . '">Borrar</a></td>
							<td><a class="btn btn-outline-success btn-lg " href="index.php?d=' . $d_act_final . '">Actualizar</a></td>
							<td><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>
							' . $Botones . '
							-->
						</tr>

							
						'
						
						;
				}
			}
			$html .= '
			<tr>
								<th class="text-center bg-dark " colspan="9"><a class="btn btn-outline-warning px-5 text-white" href="index.php?d=' . $d_act_final  . '">Matricular</a></th>
							</tr>
			</table>';
		}else{
		

		}

		
		
		return $html;
		
	}
	
	
	public function get_detail_vehiculo($id){
		$sql = "SELECT v.placa, m.descripcion as marca, v.motor, v.chasis, v.combustible, v.anio, c.descripcion as color, v.foto, v.avaluo  
				FROM vehiculo v, color c, marca m 
				WHERE v.id=$id AND v.marca=m.id AND v.color=c.id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
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
						<th class="text-center bg-dark text-white">DATOS DEL VEHÍCULO</th>
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
						<th class="text-center"><img src=imagenes/autos/' . $row['foto'] . ' width="300px"/></th>
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
			
			$html = '
			<table class="table col-lg-2" border="1" align="center">
				<tr>
					<th class="text-center bg-dark text-white" colspan="5">DATOS DEL VEHÍCULO</th>
				</tr>
				<tr>
					<th class="text-center">Placa</th>
					<th class="text-center">Fecha</th>
					<th class="text-center">Agencia</th>
					<th class="text-center">Año</th>
				</tr>';
	
	
			$found = false;
	
	
			foreach ($_SESSION['Matriculados'] as $matriculaVehiculo) {
				
				if ($matriculaVehiculo->placa == $id) {

					foreach ($matriculaVehiculo->datos as $data) {
						$html .= '
						<tr>
							<td class="text-center">' . $matriculaVehiculo->placa . '</td>
							<td class="text-center">' . $data['fecha'] . '</td>
							<td class="text-center">' . $data['agencia'] . '</td>
							<td class="text-center">' . $data['anio'] . '</td>
						</tr>';
					}
					$found = true; 
					break; 
				}
			}
	
		
			if (!$found) {
				$html .= '
				<tr>
					<td class="text-center" colspan="4">No se encontraron registros para la placa: ' . $id . '</td>
				</tr>';
			}
	
			$html .= '
				<tr>
					<th class="text-center" colspan="4"><a class="btn btn-outline-success" href="index.php">Regresar</a></th>
				</tr>
			</table>';
	
			return $html;
		}
	}
	
	public function delete_vehiculo($id){
		$sql = "DELETE FROM matricula WHERE id=$id;";
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
		   <th class=" bg-dark text-white py-5" scope="col">El registro se ' . $tipo . ' correctamente</th>
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

