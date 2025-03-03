


<?php
class vehiculo{
	private $id;
	private $descripcion;
	private $pais;
	private $direccion;
	private $foto;

	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_vehiculo() **************************************************	
	
	public function update_vehiculo(){
		$this->id = $_POST['id'];
		$this->descripcion = $_POST['descripcion'];
		$this->pais = $_POST['pais'];
		$this->direccion = $_POST['direccion'];

		
		
		
		$sql = "UPDATE marca SET descripcion='$this->descripcion',
								pais='$this->pais',
								direccion='$this->direccion'	 		
				 WHERE id=$this->id;";
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
		$this->descripcion = $_POST['descripcion'];
		$this->pais = $_POST['pais'];
		$this->direccion = $_POST['direccion'];
	
		
		
		 /*
				echo "<br> FILES <br>";
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";
		     
		*/
		
		$this->foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		$path = "../../../imagenes/Sellos/" . $this->foto;
		
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],$path)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
		
		$sql = "INSERT INTO marca VALUES(NULL,
											
											'$this->descripcion',
											'$this->pais',
											'$this->direccion',
											'$this->foto');";
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
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto){
		$html = '<select name="' . $nombre . '">';
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
			$this->id = NULL;
			$this->descripcion = NULL;
			$this->pais = NULL;
			$this->direccion = NULL;
			$this->foto = NULL;

			
			$flag = NULL;
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM marca WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar el marca con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
              // ***** TUPLA ENCONTRADA *****
				/*echo "<br>TUPLA <br>";
				echo "<pre>";
					print_r($row);
				echo "</pre>";
			
			*/

                $this->descripcion = $row['descripcion'];
                $this->pais = $row['pais'];
                $this->direccion = $row['direccion'];
                $this->foto = $row['foto'];
				
				$flag = "disabled";
				$op = "update";
			}
		}
		
		

		$html = '

		
		<form class="col-lg-5 col-ms-5" name="Form_vehiculo" method="POST" action="index.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
			<table class="table " border="2" align="center">
				<tr>
					<th class="text-center bg-dark text-white scope="col">DATOS Marca</th>
				</tr>
		

				<tr > 

					<td>
					<label for="staticEmail" class="col-sm-3 col-form-label">Descripcion:</label>
					<input type="text" size="15" name="descripcion" value="' . $this->descripcion . '"></td>
				</tr>	
				<tr>
					
					<td> 
					<label for="staticEmail" class="col-sm-3 col-form-label">Pais :</label>
					<input type="text" size="15" name="pais" value="' . $this->pais . '"></td>
				</tr>
				<tr>
					
					<td>  
					<label for="staticEmail" class="col-sm-3 col-form-label">Direccion :</label>
					<input type="text" size="15" name="direccion" value="' . $this->direccion . '"></td>
				</tr>
				<tr>
					
					<td>  
					<label for="staticEmail" class="col-sm-3 col-form-label">Fotos :</label>
					<input class="form-control-plaintext"  type="file" name="foto" ' . $flag . '></td>
				</tr>
	
				<tr>
					<th class="text-center" colspan="2"><input class="btn btn-outline-success" type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>
</form>
			';

		return $html;
	}
	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '

   
		<table class="table"  border="2" align="center" >

			<thead>
				<tr>
					<th class="text-center bg-dark text-white" scope="col" colspan="8"><h3>Lista de Marcas</h3></th>
				</tr>
				<tr>
					<th class="text-center bg-dark " colspan="8"><a  class="btn btn-outline-warning px-5 text-white" href="index.php?d=' . $d_new_final . '">Ingresar una nueva Marca</a></th>
				</tr>
					<tr class="text-center bg-dark text-white">
					<th  class="text-center bg-dark text-white scope="col" >Id</th>
					<th  class="text-center bg-dark text-white scope="col" >Descripcion</th>
					<th  class="text-center bg-dark text-white scope="col" >Pais</th>
					<th  class="text-center bg-dark text-white scope="col" >Direccion</th>
					<th  class="text-center bg-dark text-white  scope="col" >Foto</th>
					<th  class="text-center bg-dark text-white scope="col" colspan="3">Acciones</th>
				</tr>
			</thead>
		';
		$sql = "SELECT * FROM matriculacionfinal.marca;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['id'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['id'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['id'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
			<tr  class="text-center">
				<td>' . $row['id'] . '</td>
				<td>' . $row['descripcion'] . '</td>
				<td>' . $row['pais'] . '</td>
				<td>' . $row['direccion'] . '</td>
				<td style="text-align: center;"><img src=../../../imagenes/Sellos/'.$row['foto'].' alt="" height="50" ></td>
				<td class="text-center " ><a class="btn btn-outline-danger custom-btn-1 btn-lg " href="index.php?d=' . $d_del_final . '">Borrar</a></td>
				<td class="text-center "  ><a class="btn btn-outline-success btn-lg " href="index.php?d=' . $d_act_final . '">Actualizar</a></td>
				<td class="text-center "  ><a class="btn btn-outline-secondary btn-lg " href="index.php?d=' . $d_det_final . '">Detalle</a></td>
			</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_vehiculo($id){
		$sql = "SELECT * FROM matriculacionfinal.marca where id=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el vehiculo con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el marca con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<table class="table col-lg-2" border="1" align="center">
						<tr>
							<th class="text-center bg-dark text-white"  colspan="2">DATOS DEL MARCA</th>
						</tr>

						<tr>
			
							
							<td class="text-center">
							<label for="staticEmail" class="col-form-label">Descripcion :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['descripcion'] .'</label>
							
							</td>
						</tr>
						<tr>
							
							<td class="text-center">
							<label for="staticEmail" class=" col-form-label">Pais :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['pais'] .'</label>
							</td>
						</tr>
						<tr>
						
							<td class="text-center">
							<label for="staticEmail" class=" col-form-label">Direccion :</label>
							<label for="staticEmail" class="col-sm-1 col-form-label">'. $row['direccion'] .'</label>
							
							</td>
						</tr>

					
						<tr >
							<td  class="text-center" style="text-align: center;"><img src=imagenes/Sellos/'.$row['foto'].' alt=""  width="300px" ></td>
						</tr>	
						<tr>
							<th class="text-center" colspan="2"><a class="btn btn-outline-success" href="index.php">Regresar</a></th>
						</tr>																						
					</table>';
				
				return $html;
		}
	}
	
	
	public function delete_vehiculo($id){
		$sql = "DELETE FROM marca WHERE id=$id;";
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

