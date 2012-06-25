
<?php 
require_once('utf8.utils.php');
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');
require_once('Pestanya.php');
require_once('PestanyaService.php');
require_once('InformeXLS.php');
require_once('InformeXLSService.php');
require_once('Respuesta.php');

$_guardar = true;
$_buscar = true;

$DEBUG = true;


$MSG_ERR_operacionNOInformada = "Upss!! No se ha indicado la operación...";
$MSG_ERR_id_NOInformada       = "Upss!! El identificativo del informe no viene informado...";
$MSG_ERR_nombre_NOInformado   = "Upss!! El nombre del informe no viene informado...";


$_op = $_REQUEST["op"];

if($DEBUG) error_log("Operacion: [".$_op."]");

if($_op == null || $_op == ""){
	$_guardar = false;
	print($MSG_ERR_operacionNOInformada);
}

switch ($_op){
	
	/**
	 * 
	 */
	case "createInformeXLS":
		$_id = -1;
		$_nombre = $_REQUEST["nombre"];
		$_pestanyes = $_REQUEST['pestanyes'];
		
		if(is_array($_pestanyes)) error_log("Es array!");
		
		foreach($_pestanyes as $key => $value){
			error_log($key."__".$value."\n");
		}

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}


		if($_guardar){
			$informe = new InformeXLS();
			$informe->nombre = $_nombre;

			$informeXLSService = new InformeXLSService();
			$_id = $informeXLSService->save($informe);
			
			$respuesta = new Respuesta();
			if($_id == -1){
				// error al guardar el informe...
				$respuesta->codigo = "91";
				$respuesta->mensaje = "Error al guardar el informe...";
				$str = json_encode(convertArrayKeysToUtf8(get_object_vars($respuesta)));
				error_log($respuesta->mensaje);
				print($str);
				break;				
			}
			
			
			if(!$_pestanyes){
				// Informe guardado sin pestañas...
				$respuesta->codigo = "92";
				$respuesta->mensaje = "Upss!! parece que no ha llegado nada en pestanyes.";
				$str = json_encode(convertArrayKeysToUtf8(get_object_vars($respuesta)));
				error_log($respuesta->mensaje);
				print($str);
				break;				
			}
			
				
			if(!is_array($_pestanyes)){
				// Informe guardado sin pestañas...
				$respuesta->codigo = "93";
				$respuesta->mensaje = "Upss!! parece que pestanyes no es un array.";
				$str = json_encode(convertArrayKeysToUtf8(get_object_vars($respuesta)));
				error_log($respuesta->mensaje);
				print($str);
				break;				

			}
			
			$order = 1;
			foreach($_pestanyes as $key => $value){
				error_log("Pestaña: ".$value."\n");
				if($informeXLSService->addPestanyaEnInforme($_id, $value, $order)){
					error_log("Guardada la pestaña : ".$value." para el informe  ".$_nombre."");
					$order++;
				}else{
					error_log("ERROR al guardar la pestaña : ".$value." para el informe  ".$_nombre."");
				}
			}
		}

		break;

	case "get":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print($MSG_ERR_id_NOInformada);
		}

		if($_buscar){
			$informeXLSService = new InformeXLSService();
			$informeXLS = $informeXLSService->getById($_id);
			
			error_log("Recuperamos el informe:".$_id);
			
			$result = $informeXLSService->getPestanyesNoEnInforme($_id);
			error_log("Recuperamos el informe:".$_id." con ".$result->length()." pestanyes!");
			foreach($result as $pestanyaDisponible){
				error_log($pestanyaDisponible->toString());
			}

			$str = json_encode(convertArrayKeysToUtf8(get_object_vars($informeXLS)));
			print($str);
		}

		break;

	case "edit":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print($MSG_ERR_id_NOInformada);
		}
			
		if($_buscar){
			$informeXLSService = new InformeXLSService();
			$informeXLS = $informeXLSService->getById($_id);
			
			$pestanyesEnInforme = $informeXLSService->getPestanyesByInforme($_id);
			// Se puede dar el caso de que no haya pestañas asignadas en el informe
			if(isset($pestanyesEnInforme) && is_array($pestanyesEnInforme) && count($pestanyesEnInforme) > 0){
				foreach ($pestanyesEnInforme as $pestanya){
					$informeXLS->listaPestanya[] = convertArrayKeysToUtf8(get_object_vars($pestanya));
					if($DEBUG) error_log("En Informe: ".$pestanya->nombre);
				}
			}else{
				$pestanya = new Pestanya();
				$pestanya->id = 0;
				$pestanya->nombre = "Sin pestañas asignadas!";
				$informeXLS->listaPestanya[] = convertArrayKeysToUtf8(get_object_vars($pestanya));
			}
			
			$respuesta = new Respuesta();
			$respuesta->codigo = "00";
			$respuesta->mensaje = "Recuperado el informe ".$_id."!";
			
			if($DEBUG) error_log("Recuperamos el informe:".$_id);
			
			$result = $informeXLSService->getPestanyesNoEnInforme($_id);
			foreach($result as $pestanyaDisponible){
				if($DEBUG) error_log($pestanyaDisponible->nombre);
				$respuesta->listaPestanyaDisponible[] = convertArrayKeysToUtf8(get_object_vars($pestanyaDisponible));
			}

			if($DEBUG) error_log("edit:informe:".$informeXLS->toString());
			$respuesta->listaInformeXLS[] = convertArrayKeysToUtf8(get_object_vars($informeXLS));
			
			$str = json_encode($respuesta);
			error_log($str);
			print( $str );
		}
			
		break;
			
	case "del":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print($MSG_ERR_id_NOInformada);
		}
		
		if($_buscar){
			$informeXLSService = new InformeXLSService();
			$informe = $informeXLSService->getById($_id);
			
			if($DEBUG) error_log($informe->toString());

			$respuesta = new Respuesta();
			
			if($informe->id == $_id){
				if($DEBUG) error_log("La informe ".$_id." existe!");
				if($informeXLSService->delete($informe->id)){
					$respuesta->codigo = "00";
					$respuesta->mensaje = "Pestaña eliminada correctamente!";
					
				}else{
					$respuesta->codigo = "99";
					$respuesta->mensaje = "Error al eliminar la consulta!";
				}
			}else{
				$respuesta->codigo = "98";
				$respuesta->mensaje = "No existe la consulta [".$_id."]!";
			}

			$str = json_encode(convertArrayKeysToUtf8(get_object_vars($respuesta)));
			print($str);
		}
			
		break;


	case "updateInformeXLS":
		$_nombre = $_REQUEST["nombre"];
		$_id = $_REQUEST["id"];
		
		$_pestanyes = $_REQUEST['pestanyes'];
		
		if(is_array($_pestanyes)) error_log("Es array!");
		
		foreach($_pestanyes as $key => $value){
			error_log($key."__".$value."\n");
		}

		if($_id == null || $_id == ""){
			$_guardar = false;
			print($MSG_ERR_id_NOInformada);
		}

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}


		if($_guardar){
			if($DEBUG) error_log("Llegamos a guardar!");

			$informeXLSService = new InformeXLSService();
			$informeXLS = $informeXLSService->getById($_id);
			if($DEBUG) error_log("Recuperamos el informe ".$_id." : ".$informeXLS->toString());
			
			$informeXLS->nombre = $_nombre;
			
			if($DEBUG) error_log("Modificamos el informeXLS ".$_id." : ".$informeXLS->toString());
			
			if($_pestanyes){
				if(is_array($_pestanyes)){
					if($informeXLSService->deletePestanyesEnInforme($_id)){
						$order = 1;
						foreach($_pestanyes as $key => $value){
							error_log("Pestaña: ".$value."\n");
							if($informeXLSService->addPestanyaEnInforme($_id, $value, $order)){
								error_log("Guardada la pestaña : ".$value." para el informe  ".$_nombre."");
								$order++;
							}else{
								error_log("ERROR al guardar la pestaña : ".$value." para el informe  ".$_nombre."");
							}
						}
						
					}else{
						error_log("Error al borrar las pestañas del informe ".$_id);
					}
				}else{
					error_log("Upss!! parece que pestanyes no es un array.");
				}
			}else{
				error_log("Upss!! parece que no ha llegado nada en pestanyes.");
			}
			
			$respuesta = new Respuesta();
			if($informeXLSService->update($informeXLS)){
				$respuesta->codigo = "00";
				$respuesta->mensaje = "Informe guardado correctamente!";
			}else{
				$respuesta->codigo = "01";
				$respuesta->mensaje = "Upss! Error al modificar el informe...".$informeXLS->id;
			}

			if($DEBUG) error_log("".$respuesta->toString());
			$str = json_encode($respuesta);
			print($str);


		}

		break;
		
	case "searchInformeXLS":
		$_nombre = $_REQUEST["s"];
		
		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}
		
		$informeXLSService = new InformeXLSService();
		if($DEBUG) error_log("Recuperamos los informes con nombre [".$_nombre."]");
		$result = $informeXLSService->getByNombre($_nombre);
		if($DEBUG) error_log("RESULTADO: [".count($result)."]");
		

		$respuesta = new Respuesta();
		$respuesta->codigo = "00";
		if($result == null){
			$respuesta->mensaje = "No se han localizado informes!";
				
		}else{
			$respuesta->mensaje = "Recuperados [".count($result)."] informes!";
			foreach($result as $informeXLS){
				if($DEBUG) error_log("searchInforme:Informe:".$informeXLS->toString());
				$arrInformeXLS = get_object_vars($informeXLS);
// 				if($DEBUG) error_log("searchInforme:Informe:".$arrInformeXLS->length);
				if($arrInformeXLS){
					$respuesta->listaInformeXLS[] = convertArrayKeysToUtf8($arrInformeXLS);
				}
			}
				
		}
		
		print(json_encode(get_object_vars($respuesta)));
		
		break;


	default:
		break;
}






//include_once 'insert.php';
?>
