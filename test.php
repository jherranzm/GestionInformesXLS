<?php
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');

require_once('Pestanya.php');
require_once('PestanyaService.php');

header('Content-Type: text/html; charset=utf-8');


// // $cons = new ConsultaSQL();
// $consService = new ConsultaSQLService();
// $idConsulta = 6;
// print($idConsulta."!!<br>");
// $cons = $consService->getById($idConsulta);
// print($cons."<br>!!");
// print("Fin<br>!!");

// $pestanyaService = new PestanyaService();
// $cons = $pestanyaService->listAll();
// print($cons."<br>!!");
// print("Fin<br>!!");

$consService = new ConsultaSQLService();

$lista = array("Klaatu", "CCF", "TRF", "INT");
foreach ($lista as $str){
	print("Buscando..".$str."<br/>");
	$result = $consService->getByNombre($str);
	if($result == null){
		print("No se encontró nada con nombre ".$str."<br/>");
	}else{
		foreach( $result as $cons){
			print($cons->id." ".$cons->nombre."<br/>");
		}
	}
	print("**************************"."<br/>");
}

?>