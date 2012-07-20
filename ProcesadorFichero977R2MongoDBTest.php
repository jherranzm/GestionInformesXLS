<?php

require_once "ProcesadorFichero977R2MongoDB.php";

echo memory_get_usage() .PHP_EOL;

$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";

$proc = new ProcesadorFichero977R2MongoDB($file);

$proc->execute();

print_r($proc->datosAdministrativos);

$proc->mongoSave($proc->registros, "Detalle977R");

//Detalle977R

echo memory_get_usage() .PHP_EOL;
?>