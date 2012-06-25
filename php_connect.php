
<?php 

require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');

$_guardar = true;

$_nombre = $_POST["nombre"];
$_consultaSQL = $_POST["consultaSQL"];

if($_nombre == null || $_nombre == ""){
	$_guardar = false;
	alert("Vaya nombre no viene informado!!!");
}

if($_consultaSQL == null || $_consultaSQL == ""){
	$_guardar = false;
	alert("Vaya consultaSQL no viene informado!!!");
}

if($_guardar){
	$cons = new ConsultaSQL();
	$cons->Nombre = $_POST["nombre"];
	$cons->Definicion = $_POST["consultaSQL"];
	
	$consService = new ConsultaSQLService();
	$consService->save($cons);	
}



include_once 'insert.php';
?>
