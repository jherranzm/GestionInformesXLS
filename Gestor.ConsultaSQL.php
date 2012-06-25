
<?php 
require_once('utf8.utils.php');
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');
require_once('Pestanya.php');
require_once('PestanyaService.php');
require_once('Respuesta.php');

$_guardar = true;
$_buscar = true;

$_op = $_REQUEST["op"];

if($_op == null || $_op == ""){
	$_guardar = false;
	print("Vaya, no se ha indicado la operación!!!");
}

switch ($_op){
	case "createConsultaSQL":
		$_nombre = $_REQUEST["nombre"];
		$_consultaSQL = $_REQUEST["consultaSQL"];

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print("Vaya nombre no viene informado!!!");
		}

		if($_consultaSQL == null || $_consultaSQL == ""){
			$_guardar = false;
			print("Vaya consultaSQL no viene informado!!!");
		}

		if($_guardar){
			$cons = new ConsultaSQL();
			$cons->nombre = $_POST["nombre"];
			$cons->definicion = $_POST["consultaSQL"];

			$consService = new ConsultaSQLService();
			$consService->save($cons);
		}

		break;

	case "get":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print("Vaya el identificativo de la consulta no viene informado!!!");
		}

		if($_buscar){
			$consService = new ConsultaSQLService();
			$cons = $consService->getById($_id);

			$str = json_encode(convertArrayKeysToUtf8(get_object_vars($cons)));
			print($str);
		}

		break;

	case "edit":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print("Vaya el identificativo de la consulta no viene informado!!!");
		}
			
		if($_buscar){
			$consService = new ConsultaSQLService();
			$cons = $consService->getById($_id);

			$str = json_encode(convertArrayKeysToUtf8(get_object_vars($cons)));
			print($str);
		}
			
		break;
			
	case "del":
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_buscar = false;
			print("Vaya el identificativo de la consulta no viene informado!!!");
		}
			
		if($_buscar){
			$consService = new ConsultaSQLService();
			$cons = $consService->getById($_id);
			
			error_log($cons->toString());

			$respuesta = new Respuesta();
			
			if($cons->id == $_id){
				error_log("La consulta ".$_id." existe!");
				if($consService->delete($cons->id)){
					$respuesta->codigo = "00";
					$respuesta->mensaje = "ConsultaSQL eliminada correctamente!";
					
				}else{
					$respuesta->codigo = "99";
					$respuesta->mensaje = "Error al eliminar la consulta!";
				}
			}else{
				$respuesta->codigo = "98";
				$respuesta->mensaje = "No existe la consulta [".$_id."]!";
			}

			print(json_encode(get_object_vars($respuesta)));
		}
			
		break;


	case "updateConsultaSQL":
		$_nombre = $_REQUEST["nombre"];
		$_consultaSQL = $_REQUEST["consultaSQL"];
		$_id = $_REQUEST["id"];

		if($_id == null || $_id == ""){
			$_guardar = false;
			print("Vaya el identificativo no viene informado!!!");
		}

		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print("Vaya nombre no viene informado!!!");
		}

		if($_consultaSQL == null || $_consultaSQL == ""){
			$_guardar = false;
			print("Vaya consultaSQL no viene informado!!!");
		}

		if($_guardar){
			// 				$cons = new ConsultaSQL();
			// 				$cons->nombre = $_POST["nombre"];
			// 				$cons->definicion = $_POST["consultaSQL"];
			
			error_log("Llegamos a guardar!");

			$consService = new ConsultaSQLService();
			$cons = $consService->getById($_id);
			error_log("Recuperamos la consulta ".$_id.":".$cons->toString());
			$cons->nombre = $_nombre;
			$cons->definicion = $_consultaSQL;
			error_log("Modificamos la consulta ".$_id.":".$cons->toString());
			$consService->update($cons);

			$respuesta = new Respuesta();
			$respuesta->codigo = "00";
			$respuesta->mensaje = "ConsultaSQL guardada correctamente!";

			print(json_encode(get_object_vars($respuesta)));


		}

		break;
		
	case "searchConsultaSQL":
		$_nombre = $_REQUEST["s"];
		
		if($_nombre == null || $_nombre == ""){
			$_guardar = false;
			print("Vaya nombre no viene informado!!!");
		}
		
		$consService = new ConsultaSQLService();
		error_log("Recuperamos las consultas con nombre ".$_nombre);
		$result = $consService->getByNombre($_nombre);
		error_log("RESULTADO: ".count($result));
		

		$respuesta = new Respuesta();
		$respuesta->codigo = "00";
		if($result == null){
			$respuesta->mensaje = "No se han localizado consultas!";
				
		}else{
			$respuesta->mensaje = "Recuperadas ".count($result)." consultas!";
			foreach($result as $consulta){
				$respuesta->listaConsultaSQL[] = convertArrayKeysToUtf8(get_object_vars($consulta));
			}
				
		}
		
// 		error_log("RESULTADO: ".json_encode(get_object_vars($respuesta)));
		print(json_encode(get_object_vars($respuesta)));
		
		break;

	case "getAll":
		
			$consService = new ConsultaSQLService();
			error_log("Recuperamos todas las consultas ");
			$result = $consService->listAll();
			error_log("RESULTADO: ".count($result));
		
			$respuesta = new Respuesta();
			$respuesta->codigo = "00";
			if($result == null){
				$respuesta->mensaje = "No se han localizado consultas!";
		
			}else{
				$respuesta->mensaje = "Recuperadas ".count($result)." consultas!";
				foreach($result as $consulta){
					$respuesta->listaConsultaSQL[] = convertArrayKeysToUtf8(get_object_vars($consulta));
				}
		
			}
		
			// 		error_log("RESULTADO: ".json_encode(get_object_vars($respuesta)));
			print(json_encode(get_object_vars($respuesta)));
		
			break;
		
		
		
	default:
		break;
}




//include_once 'insert.php';
?>
