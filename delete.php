
<?php 

require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');

$op = $_GET["op"];
$_id = $_GET["id"];

$consService = new ConsultaSQLService();
$consService->delete($_id);

include_once 'insert.php';
?>
