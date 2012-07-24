<?php

require_once "ProcesadorFichero977R2Excel.php";

echo memory_get_usage() .PHP_EOL;

$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";
$file = "/Users/jherranzm/Downloads/LA08000234_20120628.zip";

$proc = new ProcesadorFichero977R2Excel($file);

$proc->execute();

print_r($proc->datosAdministrativos);

$proc->saveToExcel();
//Detalle977R

echo memory_get_usage() .PHP_EOL;
?>