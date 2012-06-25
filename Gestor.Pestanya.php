
<?php 
require_once('utf8.utils.php');
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');
require_once('Pestanya.php');
require_once('PestanyaService.php');
require_once('Respuesta.php');

$_guardar = true;
$_buscar = true;

$DEBUG = FALSE;


$MSG_ERR_operacionNOInformada = "Upss!! No se ha indicado la operación...";
$MSG_ERR_id_NOInformada       = "Upss!! El identificativo de la pestaña no viene informado...";
$MSG_ERR_nombre_NOInformado   = "Upss!! El nombre de la pestaña no viene informado...";
$MSG_ERR_consulta_NOInformada = "Upss!! La consulta de la pestaña no viene informada...";


$_op = $_REQUEST["op"];

if($_op == null || $_op == ""){
	$_guardar = false;
	print($MSG_ERR_operacionNOInformada);
}

switch ($_op){
	
	case "createPestanya":
		$_nombre = $_REQUEST["nombre"];
		$_consultaSQL = $_REQUEST["consultaSQL"];

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}

		if($_consultaSQL == null || $_consultaSQL == ""){
			$_guardar = false;
			print($MSG_ERR_consulta_NOInformada);
		}

		if($_guardar){
			$cons = new Pestanya();
			$cons->nombre = $_POST["nombre"];
			$cons->definicion = $_POST["consultaSQL"];

			$pestanyaService = new PestanyaService();
			$pestanyaService->save($cons);
		}

		break;

	case "get":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print($MSG_ERR_id_NOInformada);
		}

		if($_buscar){
			$pestanyaService = new PestanyaService();
			$pestanya = $pestanyaService->getById($_id);

			$str = json_encode(convertArrayKeysToUtf8(get_object_vars($pestanya)));
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
			$pestanyaService = new PestanyaService();
			$pestanya = $pestanyaService->getById($_id);
			
			$consService = new ConsultaSQLService();
			$consultas = $consService->listAll();
			
			$respuesta = new Respuesta();
			$respuesta->codigo = "00";
			$respuesta->mensaje = "Recuperada 1 pestaña!";
			error_log("searchPestanya:Pestanya:".$pestanya->toString());
			//$respuesta->listaPestanya[] = $pestanya;
			$respuesta->listaPestanya[] = convertArrayKeysToUtf8(get_object_vars($pestanya));
			
			foreach($consultas as $consulta){
				error_log("edit:consulta:".$consulta->toString());
				//$respuesta->listaPestanya[] = $pestanya;
				$respuesta->listaConsultaSQL[] = convertArrayKeysToUtf8(get_object_vars($consulta));
			}

			print(json_encode(get_object_vars($respuesta)));
		}
			
		break;
			
	case "del":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print($MSG_ERR_id_NOInformada);
		}
		
		if($_buscar){
			$pestanyaService = new PestanyaService();
			$cons = $pestanyaService->getById($_id);
			
			if($DEBUG) error_log($cons->toString());

			$respuesta = new Respuesta();
			
			if($cons->id == $_id){
				if($DEBUG) error_log("La pestaña ".$_id." existe!");
				if($pestanyaService->delete($cons->id)){
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


	case "updatePestanya":
		$_nombre = $_REQUEST["nombre"];
		$_rango = $_REQUEST["rango"];
		$_numfilainicial = $_REQUEST["numFilaInicial"];
		$_rango = $_REQUEST["rango"];
		$_consultaSQL = $_REQUEST["consultaSQLid"];
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_guardar = false;
			print($MSG_ERR_id_NOInformada);
		}

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}

		if($_rango == null || $_rango == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}
		if($_numfilainicial == null || $_numfilainicial == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}
		
		if($_consultaSQL == null || $_consultaSQL == ""){
			$_guardar = false;
			print($MSG_ERR_consulta_NOInformada);
		}

		if($_guardar){
			// 				$cons = new Pestanya();
			// 				$cons->nombre = $_POST["nombre"];
			// 				$cons->definicion = $_POST["consultaSQL"];
			
			if($DEBUG) error_log("Llegamos a guardar!");

			$pestanyaService = new PestanyaService();
			$pestanya = $pestanyaService->getById($_id);
			if($DEBUG) error_log("Recuperamos la consulta ".$_id." : ".$pestanya->toString());
			
			$pestanya->nombre = $_nombre;
			$pestanya->rango = $_rango;
			$pestanya->numfilainicial = $_numfilainicial;
			$pestanya->consultaid = $_consultaSQL;
			
			if($DEBUG) error_log("Modificamos la pestanya ".$_id." : ".$pestanya->toString());
			$respuesta = new Respuesta();
			if($pestanyaService->update($pestanya)){
				$respuesta->codigo = "00";
				$respuesta->mensaje = "Pestanya guardada correctamente!";
			}else{
				$respuesta->codigo = "01";
				$respuesta->mensaje = "Upss! Error al modificar la pestaña...".$pestanya->id;
			}

			if($DEBUG) error_log("".$respuesta->toString());
			$str = json_encode($respuesta);
			print($str);


		}

		break;
		
	case "searchPestanya":
		$_nombre = $_REQUEST["s"];
		
		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print($MSG_ERR_nombre_NOInformado);
		}
		
		$pestanyaService = new PestanyaService();
		if($DEBUG) error_log("Recuperamos las pestañas con nombre ".$_nombre);
		$result = $pestanyaService->getByNombre($_nombre);
		if($DEBUG) error_log("RESULTADO: ".count($result));
		

		$respuesta = new Respuesta();
		$respuesta->codigo = "00";
		if($result == null){
			$respuesta->mensaje = "No se han localizado pestañas!";
				
		}else{
			$respuesta->mensaje = "Recuperadas ".count($result)." pestañas!";
			foreach($result as $pestanya){
				if($DEBUG) error_log("searchPestanya:Pestanya:".$pestanya->toString());
				//$respuesta->listaPestanya[] = $pestanya;
				$respuesta->listaPestanya[] = convertArrayKeysToUtf8(get_object_vars($pestanya));
			}
				
		}
		
		print(json_encode(get_object_vars($respuesta)));
		
		break;

	case "getAll":
	
		$pestanyaService = new PestanyaService();
		if($DEBUG) error_log("Recuperamos todas las pestañas ");
		$result = $pestanyaService->listAll();
	
		$respuesta = new Respuesta();
		if($result == null){
			$respuesta->codigo = "01";
			$respuesta->mensaje = "No se han localizado pestañas!";
		}else{
			$respuesta->codigo = "00";
			$respuesta->mensaje = "Recuperadas ".count($result)." pestañas!";
			foreach($result as $pestanya){
				$respuesta->listaPestanyaDisponible[] = convertArrayKeysToUtf8(get_object_vars($pestanya));
			}
		}
	
		print(json_encode(get_object_vars($respuesta)));
	
		break;
		
		
	default:
		break;
}






//include_once 'insert.php';
?>
