<?php

require_once "ProcesadorFichero977R.php";

$file = "/Users/jherranzm/Downloads/Fichero_Ejemplo.zip";

$proc = new ProcesadorFichero977R($file);

$proc->execute();

print_r($proc->datosAdministrativos);
?>