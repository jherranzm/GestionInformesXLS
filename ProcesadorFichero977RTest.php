<?php

require_once "ProcesadorFichero977R.php";

echo memory_get_usage() .PHP_EOL;

$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";

$proc = new ProcesadorFichero977R($file);

$proc->execute();

print_r($proc->datosAdministrativos);
print_r($proc->registros);

echo memory_get_usage() .PHP_EOL;
?>